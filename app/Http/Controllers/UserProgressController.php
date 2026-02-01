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
    public function index(Request $request, Lesson $lesson)
    {
        // 1. Cek apakah di lesson ini ada soal Essay?
        $hasEssayQuestion = $lesson->questions()->where('type', 'essay')->exists();

        // 2. Query Dasar
        $query = User::whereHas('answers', function($query) use ($lesson) {
            $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            });
        });

        // 3. LOGIC FILTER STATUS (Ini yang membuat dropdown berfungsi)
        if ($request->status == 'pending') {
            // Cari user yang punya jawaban essay yang belum diupdate (created_at == updated_at)
            $query->whereHas('answers', function($q) use ($lesson) {
                $q->whereHas('question', function($subQ) use ($lesson) {
                    $subQ->where('lesson_id', $lesson->id)->where('type', 'essay');
                })->whereColumn('created_at', 'updated_at');
            });
        } elseif ($request->status == 'graded') {
            // Cari user yang TIDAK punya jawaban essay pending
            $query->whereDoesntHave('answers', function($q) use ($lesson) {
                $q->whereHas('question', function($subQ) use ($lesson) {
                    $subQ->where('lesson_id', $lesson->id)->where('type', 'essay');
                })->whereColumn('created_at', 'updated_at');
            });
        }

        // 4. Ambil data dengan relasi
        $users = $query->with(['answers' => function($query) use ($lesson) {
            $query->whereHas('question', function($q) use ($lesson) {
                $q->where('lesson_id', $lesson->id);
            })->with('question'); 
        }])->paginate(10);

        // 5. Loop untuk label status (sama seperti sebelumnya)
        foreach ($users as $user) {
            $user->total_score = $user->answers->sum('score');
            
            if (!$hasEssayQuestion) {
                $user->grading_status = 'Sudah Dinilai';
            } else {
                $needsGrading = $user->answers->contains(function ($ans) {
                    return $ans->question->type === 'essay' && $ans->updated_at->eq($ans->created_at);
                });

                if ($needsGrading) {
                    $user->grading_status = 'Perlu Koreksi';
                } else {
                    $user->grading_status = 'Sudah Dinilai';
                }
            }
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
     * UPDATE MASSAL: Simpan semua nilai sekaligus.
     */
    public function updateAllScores(Request $request, Lesson $lesson, User $user)
    {
        $request->validate([
            'scores' => 'nullable|array', 
            'scores.*' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $userAnswers = UserAnswer::with('question')
                ->where('user_id', $user->id)
                ->whereHas('question', function($q) use ($lesson) {
                    $q->where('lesson_id', $lesson->id);
                })->get();

            foreach ($userAnswers as $ans) {
                $score = 0;
                $isCorrect = false;

                if ($ans->question->type == 'multiple_choice') {
                    // Auto-grade PG (Safety check, biar nilai gak ketimpa)
                    if ($ans->answer === $ans->question->correct_answer) {
                        $score = $ans->question->points;
                        $isCorrect = true;
                    }
                } else {
                    // Manual grade Essay
                    $inputScore = $request->scores[$ans->id] ?? $ans->score;
                    $score = min($inputScore, $ans->question->points); // Cegah nilai lebih dari bobot
                    $isCorrect = $score > 0;
                }

                // Assign nilai baru
                $ans->score = $score;
                $ans->is_correct = $isCorrect;
                
                // [PENTING] Update timestamp 'updated_at'
                // Ini kuncinya: Walaupun nilai tidak berubah (misal tetap 0),
                // kita paksa update waktu agar status berubah jadi 'Sudah Dinilai'.
                if ($ans->isDirty()) {
                    $ans->save();
                } else {
                    $ans->touch(); // Paksa update timestamp jika data tidak berubah
                }
            }

            DB::commit();

            return redirect()->route('admin.lessons.users.index', $lesson->id)
                ->with('success', 'Penilaian disimpan. Status siswa diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update skor satuan (Optional)
     */
    public function updateScore(Request $request, UserAnswer $userAnswer)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:' . $userAnswer->question->points,
        ]);

        $userAnswer->score = $request->score;
        $userAnswer->is_correct = $request->score > 0;
        
        if ($userAnswer->isDirty()) {
            $userAnswer->save();
        } else {
            $userAnswer->touch();
        }

        return back()->with('success', 'Nilai diperbarui.');
    }
}