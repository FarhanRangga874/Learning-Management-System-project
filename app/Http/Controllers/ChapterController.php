<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChapterController extends Controller
{
    /**
     * Menampilkan daftar Bab milik Kursus tertentu
     */
    public function index(Course $course)
    {
        // Menampilkan daftar bab urut berdasarkan ID
        $chapters = $course->chapters()->orderBy('id', 'asc')->get();
        return view('admin.chapters.index', compact('course', 'chapters'));
    }

    /**
     * Form tambah Bab
     */
    public function create(Course $course)
    {
        return view('admin.chapters.create', compact('course'));
    }

    /**
     * Simpan Bab (Hanya Judul)
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Simpan Bab baru
        $course->chapters()->create($validated);

        return redirect()->route('admin.courses.chapters.index', $course->id)
            ->with('success', 'Bab berhasil ditambahkan!');
    }

    /**
     * Form edit Bab
     */
    public function edit(Course $course, Chapter $chapter)
    {
        return view('admin.chapters.edit', compact('course', 'chapter'));
    }

    /**
     * Update Bab (Hanya Judul)
     */
    public function update(Request $request, Course $course, Chapter $chapter)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Update Judul Bab
        $chapter->update($validated);

        return redirect()->route('admin.courses.chapters.index', $course->id)
            ->with('success', 'Bab berhasil diperbarui!');
    }

    /**
     * Hapus Bab
     */
    public function destroy(Course $course, Chapter $chapter)
    {
        // Hapus Bab (Lessons di dalamnya akan ikut terhapus jika di migration ada onDelete cascade)
        $chapter->delete();

        return redirect()->route('admin.courses.chapters.index', $course->id)
            ->with('success', 'Bab berhasil dihapus!');
    }
}