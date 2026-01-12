<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    // 1. HALAMAN DEPAN (KATALOG)
    public function index()
    {
        $courses = Course::with('category')->orderBy('id', 'desc')->get();
        return view('front.index', compact('courses'));
    }

    // 2. HALAMAN DETAIL KURSUS (GRID SYSTEM)
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
        // Cek Hak Akses
        if (!Auth::check() || !Auth::user()->hasJoined($course)) {
            return redirect()->route('front.details', $course->slug);
        }

        // Load semua bab dan materi
        $course->load(['chapters.lessons']);

        // Menentukan materi mana yang sedang dibuka
        $currentLesson = null;

        if ($lessonId) {
            // Jika user memilih materi tertentu dari sidebar
            $currentLesson = Lesson::where('id', $lessonId)->firstOrFail();
        } else {
            // Jika baru masuk, otomatis buka materi PERTAMA dari bab PERTAMA
            $firstChapter = $course->chapters->first();
            if ($firstChapter) {
                $currentLesson = $firstChapter->lessons->first();
            }
        }

        return view('front.learning', compact('course', 'currentLesson'));
    }
}