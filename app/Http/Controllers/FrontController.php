<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    // 1. HALAMAN DEPAN (KATALOG)
    public function index(Request $request)
    {
        $categories = Category::all();
        $coursesQuery = Course::with('category')->latest();

        if ($request->filled('search')) {
            $keyword = $request->search;
            $coursesQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            if ($request->category !== 'all') {
                $coursesQuery->where('category_id', $request->category);
            }
        }

        $courses = $coursesQuery->get();
        return view('front.index', compact('courses', 'categories'));
    }

    // 2. HALAMAN DETAIL KURSUS
    public function details(Course $course)
    {
        $course->load(['category', 'chapters.lessons', 'students']);
        
        $enrolled = false;
        if (Auth::check()) {
            $enrolled = Auth::user()->hasJoined($course);
        }

        return view('front.details', compact('course', 'enrolled'));
    }

    // 3. PROSES GABUNG KELAS
    public function join(Request $request, Course $course)
    {
        if (!Auth::check()) return redirect()->route('login');

        if (Auth::user()->hasJoined($course)) {
            return redirect()->route('front.learning', $course->slug);
        }

        if ($course->access_type === 'code') {
            $request->validate(['access_code' => 'required|string']);
            if ($request->access_code !== $course->access_code) {
                return back()->withErrors(['access_code' => 'Kode akses salah!']);
            }
        }

        Auth::user()->courses()->attach($course->id);

        return redirect()->route('front.learning', $course->slug)
            ->with('success', 'Berhasil bergabung!');
    }

    // 4. HALAMAN BELAJAR (CLASSROOM) - UPDATE: DENGAN LOGIC QUIZ
    public function learning(Course $course, $lessonId = null)
    {
        $userId = Auth::id();

        if (!Auth::check() || !Auth::user()->hasJoined($course)) {
            return redirect()->route('front.details', $course->slug);
        }

        $course->load(['chapters.lessons']);

        $currentLesson = null;
        if ($lessonId) {
            $currentLesson = Lesson::where('id', $lessonId)->firstOrFail();
        } else {
            $firstChapter = $course->chapters->first();
            if ($firstChapter) {
                $currentLesson = $firstChapter->lessons->first();
            }
        }

        // --- TAMBAHAN LOGIKA QUIZ / ASSIGNMENT ---
        $hasSubmitted = false;
        $totalScore = 0;

        if ($currentLesson && $currentLesson->type == 'assignment') {
            $hasSubmitted = UserAnswer::where('user_id', $userId)
                ->whereHas('question', function($q) use ($currentLesson){
                    $q->where('lesson_id', $currentLesson->id);
                })->exists();
            
            if ($hasSubmitted) {
                $totalScore = UserAnswer::where('user_id', $userId)
                    ->whereHas('question', function($q) use ($currentLesson){
                        $q->where('lesson_id', $currentLesson->id);
                    })->sum('score');
            }
        }

        return view('front.learning', compact('course', 'currentLesson', 'hasSubmitted', 'totalScore'));
    }

    // 5. MULAI QUIZ (Baru)
    public function startQuiz(Course $course, Lesson $lesson)
    {
        // Cek apakah user sudah pernah submit
        $exists = UserAnswer::where('user_id', Auth::id())
            ->whereHas('question', function($q) use ($lesson){
                $q->where('lesson_id', $lesson->id);
            })->exists();

        if ($exists) {
            return redirect()->route('front.learning', [$course->slug, $lesson->id]);
        }

        $questions = $lesson->questions;
        return view('front.quiz', compact('course', 'lesson', 'questions'));
    }

    // 6. SUBMIT JAWABAN (Baru)
    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
    {
        $userId = Auth::id();
        $answers = $request->input('answers');

        if (!$answers) {
            return back()->with('error', 'Mohon isi jawaban Anda.');
        }

        foreach ($answers as $questionId => $userAnswer) {
            $question = Question::find($questionId);
            $isCorrect = false;
            $score = 0;

            if ($question->type == 'multiple_choice') {
                if ($userAnswer == $question->correct_answer) {
                    $isCorrect = true;
                    $score = $question->points;
                }
            }

            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'answer' => $userAnswer,
                'is_correct' => $isCorrect,
                'score' => $score
            ]);
        }

        return redirect()->route('front.learning', [$course->slug, $lesson->id])
            ->with('success', 'Jawaban berhasil dikirim!');
    }
}