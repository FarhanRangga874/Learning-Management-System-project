<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB; // PENTING: Tambahkan ini untuk transaksi database

class UserProgressController extends Controller
{
    /**
     * Tampilkan daftar siswa yang sudah mengerjakan tugas ini.
     */
    public function index(Lesson $lesson)
    {
        // Ambil user yang memiliki jawaban pada lesson ini
        // Menggunakan whereHas untuk filter hanya user yang sudah submit
        $users = User::whereHas('answers', function($query) use ($lesson) {
            $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            });
        })->with(['answers' => function($query) use ($lesson) {
             // Load jawaban khusus lesson ini agar bisa dihitung total skornya
             $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            });
        }])->paginate(10);

        // Hitung total skor untuk tampilan di tabel
        foreach ($users as $user) {
            $user->total_score = $user->answers->sum('score');
            
            // Logika Status Sederhana:
            $user->grading_status = $user->total_score > 0 ? 'Sudah Dinilai' : 'Perlu Koreksi';
        }

        return view('admin.lessons.users', compact('lesson', 'users'));
    }

    /**
     * Tampilkan halaman koreksi detail jawaban satu siswa.
     */
    public function show(Lesson $lesson, User $user)
    {
        // Ambil semua jawaban siswa tersebut KHUSUS untuk lesson ini
        // Disertai data pertanyaannya (question)
        $answers = UserAnswer::with('question')
            ->where('user_id', $user->id)
            ->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            })
            ->get();

        return view('admin.lessons.grading', compact('lesson', 'user', 'answers'));
    }

    /**
     * Update skor untuk satu jawaban spesifik (aksi simpan nilai individual).
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

    /**
     * UPDATE MASSAL: Simpan semua nilai sekaligus dari halaman grading.
     */
    public function updateAllScores(Request $request, Lesson $lesson, User $user)
    {
        // Validasi input array scores
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->scores as $answerId => $score) {
                // Cari jawaban berdasarkan ID
                $userAnswer = UserAnswer::find($answerId);
                
                // Pastikan jawaban ada dan milik user yang sedang dinilai (keamanan)
                if ($userAnswer && $userAnswer->user_id == $user->id) {
                    
                    // Pastikan nilai tidak melebihi poin maksimal soal
                    if ($score <= $userAnswer->question->points) {
                        $userAnswer->update([
                            'score' => $score,
                            'is_correct' => $score > 0
                        ]);
                    }
                }
            }

            DB::commit();

            // Redirect kembali ke daftar user setelah simpan semua
            return redirect()->route('admin.lessons.users.index', $lesson->id)
                ->with('success', 'Semua nilai berhasil disimpan untuk siswa ' . $user->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage());
        }
    }
}