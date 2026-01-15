<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Category; // <--- Tambahkan Import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    // 1. HALAMAN DEPAN (KATALOG)
    public function index(Request $request)
    {
        // A. Ambil semua kategori untuk ditampilkan di tombol filter
        $categories = Category::all();

        // B. Siapkan Query Dasar (belum dieksekusi)
        // Kita gunakan latest() pengganti orderBy('id', 'desc') agar lebih rapi
        $coursesQuery = Course::with('category')->latest();

        // C. Logika PENCARIAN (Search Bar)
        if ($request->filled('search')) {
            $keyword = $request->search;
            $coursesQuery->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // D. Logika FILTER KATEGORI (Tombol Kategori)
        if ($request->filled('category')) {
            // Filter hanya jika nilai category bukan 'all'
            if ($request->category !== 'all') {
                $coursesQuery->where('category_id', $request->category);
            }
        }

        // E. Eksekusi Query
        $courses = $coursesQuery->get();

        return view('front.index', compact('courses', 'categories'));
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