<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\LessonCompletion;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    // 1. HALAMAN DEPAN (KATALOG & SIDEBAR)
    public function index(Request $request)
    {
        $categories = Category::all();
        $faqs = Faq::orderBy('ordering', 'asc')->get();

        // --- QUERY UTAMA (LIST KURSUS) ---
        $coursesQuery = Course::with(['category', 'students']);

        // 1. Search Logic
        if ($request->filled('search')) {
            $keyword = $request->search;
            $coursesQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // 2. Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $coursesQuery->where('category_id', $request->category);
        }

        if ($request->filled('access_type')) {
        $type = $request->access_type;
            if (in_array($type, ['open', 'code'])) { // Validasi input
                $coursesQuery->where('access_type', $type);
            }
        }

        // 3. Sorting Logic
        if ($request->sort == 'populer') {
            $coursesQuery->withCount('students')->orderBy('students_count', 'desc');
        } else {
            $coursesQuery->latest();
        }

        // --- PENTING: PAGINATION DIUBAH KE 12 ---
        // Angka 12 cocok untuk grid 3 kolom (3x4) maupun 4 kolom (4x3)
        $courses = $coursesQuery->paginate(12);

        return view('front.index', compact('courses', 'categories', 'faqs'));
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

    // 4. HALAMAN BELAJAR (CLASSROOM)
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

        // --- Logic Progress ---
        $allLessonIds = Lesson::whereHas('chapter', fn($q) => $q->where('course_id', $course->id))
            ->pluck('id');
        
        $totalLessons = $allLessonIds->count();

        $completedCount = LessonCompletion::where('user_id', $userId)
            ->whereIn('lesson_id', $allLessonIds)
            ->count();

        $progress = ($totalLessons > 0) ? round(($completedCount / $totalLessons) * 100) : 0;

        // Cek status completion lesson saat ini
        $isCompleted = false;
        if ($currentLesson) {
            $isCompleted = LessonCompletion::where('user_id', $userId)
                ->where('lesson_id', $currentLesson->id)
                ->exists();
        }

        // --- Logic Quiz ---
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

        return view('front.learning', compact(
            'course', 
            'currentLesson', 
            'hasSubmitted', 
            'totalScore', 
            'progress',    
            'isCompleted'  
        ));
    }

    // 5. MARK AS COMPLETE (TOMBOL SELESAI)
    public function markAsComplete(Request $request, Course $course, Lesson $lesson)
    {
        $userId = Auth::id();

        LessonCompletion::firstOrCreate([
            'user_id' => $userId,
            'lesson_id' => $lesson->id,
        ], [
            'course_id' => $course->id
        ]);

        $allLessons = Lesson::whereHas('chapter', fn($q) => $q->where('course_id', $course->id))
            ->orderBy('id', 'asc') 
            ->get();

        $currentIndex = $allLessons->search(function($item) use ($lesson) {
            return $item->id == $lesson->id;
        });

        if ($currentIndex !== false && isset($allLessons[$currentIndex + 1])) {
            $nextLesson = $allLessons[$currentIndex + 1];
            return redirect()->route('front.learning', [$course->slug, $nextLesson->id]);
        }

        return redirect()->route('front.learning', [$course->slug, $lesson->id])
            ->with('success', 'Selamat! Anda telah menyelesaikan materi terakhir.');
    }

    // 6. MULAI QUIZ
    public function startQuiz(Course $course, Lesson $lesson)
    {
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

    // 7. SUBMIT JAWABAN
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

        LessonCompletion::firstOrCreate([
            'user_id' => $userId,
            'lesson_id' => $lesson->id,
        ], [
            'course_id' => $course->id
        ]);

        return redirect()->route('front.learning', [$course->slug, $lesson->id])
            ->with('success', 'Jawaban berhasil dikirim dan ditandai selesai!');
    }
}