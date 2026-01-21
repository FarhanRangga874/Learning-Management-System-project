<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class UserProgressController extends Controller
{
    // 1. DAFTAR USER YANG MENGERJAKAN (Rekap Nilai)
    public function index(Lesson $lesson)
    {
        // Validasi tipe
        if ($lesson->type !== 'assignment') {
            return back()->with('error', 'Hanya untuk materi tugas.');
        }

        // Ambil User yang sudah mengerjakan (filter dari tabel user_answers)
        $users = User::whereHas('answers', function($q) use ($lesson) {
            $q->whereHas('question', function($sq) use ($lesson) {
                $sq->where('lesson_id', $lesson->id);
            });
        })->get();

        // Hitung total skor per user
        foreach($users as $user) {
            $user->total_score = UserAnswer::where('user_id', $user->id)
                ->whereHas('question', fn($q) => $q->where('lesson_id', $lesson->id))
                ->sum('score');
        }

        return view('admin.lessons.users', compact('lesson', 'users'));
    }

    // 2. HALAMAN KOREKSI (Detail Jawaban Satu User)
    public function show(Lesson $lesson, User $user)
    {
        // Ambil jawaban user untuk lesson ini
        $answers = UserAnswer::where('user_id', $user->id)
            ->whereHas('question', fn($q) => $q->where('lesson_id', $lesson->id))
            ->with('question')
            ->get();

        return view('admin.lessons.grading', compact('lesson', 'user', 'answers'));
    }

    // 3. UPDATE NILAI (Untuk Koreksi Essay)
    public function updateScore(Request $request, UserAnswer $userAnswer)
    {
        $request->validate([
            'score' => 'required|integer|min:0'
        ]);

        $userAnswer->update([
            'score' => $request->score,
            'is_correct' => $request->score > 0
        ]);

        return back()->with('success', 'Nilai berhasil diperbarui!');
    }
}