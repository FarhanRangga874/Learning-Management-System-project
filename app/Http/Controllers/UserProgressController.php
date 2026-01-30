<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UserProgressController extends Controller
{
    /**
     * Tampilkan daftar siswa yang sudah mengerjakan tugas ini.
     */
    public function index(Lesson $lesson)
    {
        // 1. Ambil user yang memiliki jawaban pada lesson ini
        $users = User::whereHas('answers', function($query) use ($lesson) {
            $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            });
        })->with(['answers' => function($query) use ($lesson) {
             // Load jawaban beserta detail pertanyaannya (penting untuk cek tipe soal)
             $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            })->with('question'); 
        }])->paginate(10);

        // 2. Loop setiap user untuk hitung skor & tentukan status
        foreach ($users as $user) {
            $user->total_score = $user->answers->sum('score');
            
            // --- LOGIKA STATUS BARU ---
            // Cek apakah ada soal ESSAY yang nilainya masih 0 (Asumsi 0 = belum dinilai)
            // Kita gunakan collection method 'contains'
            $hasUngradedEssay = $user->answers->contains(function ($ans) {
                return $ans->question->type === 'essay' && $ans->score === 0;
            });

            // Jika ada essay bernilai 0 -> Perlu Koreksi.
            // Jika tidak ada essay 0 (atau hanya ada PG) -> Selesai.
            $user->grading_status = $hasUngradedEssay ? 'Perlu Koreksi' : 'Selesai';
        }

        return view('admin.lessons.users', compact('lesson', 'users'));
    }

    /**
     * Tampilkan halaman koreksi detail jawaban satu siswa.
     */
    public function show(Lesson $lesson, User $user)
    {
        $answers = UserAnswer::with('question')
            ->where('user_id', $user->id)
            ->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            })
            ->get();

        return view('admin.lessons.grading', compact('lesson', 'user', 'answers'));
    }

    /**
     * UPDATE MASSAL: Auto-Grade PG & Manual Grade Essay
     */
    public function updateAllScores(Request $request, Lesson $lesson, User $user)
    {
        // Validasi: scores boleh null/kosong jika soalnya hanya Pilihan Ganda (karena PG tidak kirim input)
        $request->validate([
            'scores' => 'nullable|array', 
            'scores.*' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil semua jawaban user untuk lesson ini dari Database
            // Kita loop berdasarkan data DB, bukan data Request, agar PG tetap ternilai meski tidak ada di request
            $userAnswers = UserAnswer::with('question')
                ->where('user_id', $user->id)
                ->whereHas('question', function($q) use ($lesson) {
                    $q->where('lesson_id', $lesson->id);
                })->get();

            foreach ($userAnswers as $ans) {
                $score = 0;

                // --- LOGIKA AUTO-GRADING ---
                
                if ($ans->question->type == 'multiple_choice') {
                    // A. PILIHAN GANDA: Nilai Otomatis by System
                    // Cek apakah jawaban user SAMA PERSIS dengan kunci jawaban
                    if ($ans->answer === $ans->question->correct_answer) {
                        $score = $ans->question->points; // Dapat Poin Penuh
                    } else {
                        $score = 0; // Salah
                    }
                } 
                else {
                    // B. ESSAY: Nilai Manual dari Input Admin
                    // Ambil nilai dari input request berdasarkan ID jawaban
                    // Jika tidak ada di request (misal admin lupa isi), pakai nilai lama ($ans->score)
                    $inputScore = $request->scores[$ans->id] ?? $ans->score;
                    
                    // Pastikan nilai tidak melebihi poin maksimal soal
                    $score = min($inputScore, $ans->question->points); 
                }

                // 2. Update ke Database
                $ans->update([
                    'score' => $score,
                    'is_correct' => $score > 0 // Anggap benar jika dapat nilai > 0
                ]);
            }

            DB::commit();

            return redirect()->route('admin.lessons.users.index', $lesson->id)
                ->with('success', 'Penilaian berhasil disimpan! (Pilihan Ganda dinilai otomatis)');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update skor satuan (Opsional, jika masih dibutuhkan untuk ajax/manual satu per satu)
     */
    public function updateScore(Request $request, UserAnswer $userAnswer)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:' . $userAnswer->question->points,
        ]);

        $userAnswer->update([
            'score' => $request->score,
            'is_correct' => $request->score > 0 
        ]);

        return back()->with('success', 'Nilai berhasil diperbarui!');
    }
}