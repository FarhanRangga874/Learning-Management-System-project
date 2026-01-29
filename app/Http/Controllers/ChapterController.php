<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB; // PENTING: Untuk Transaction

class ChapterController extends Controller
{
    /**
     * Menampilkan daftar Bab milik Kursus tertentu
     */
    public function index(Course $course)
    {
        // Eager load lessons agar tidak n+1 query di view index (accordion)
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        
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
     * Simpan Bab & Materi Sekaligus
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'lessons' => 'nullable|array',
            'lessons.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $course) {
            // 1. Simpan Bab
            $chapter = $course->chapters()->create([
                'title' => $validated['title']
            ]);

            // 2. Simpan Materi (Jika ada)
            if (!empty($validated['lessons'])) {
                foreach ($validated['lessons'] as $lessonTitle) {
                    if ($lessonTitle) {
                        $chapter->lessons()->create([
                            'title' => $lessonTitle,
                            'type' => 'video', // Default type, bisa diubah nanti di edit
                            'video_source' => 'youtube', // Default
                            'is_preview' => false,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.courses.chapters.index', $course)
            ->with('success', 'Bab dan materi berhasil ditambahkan!');
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

        $chapter->update($validated);

        return redirect()->route('admin.courses.chapters.index', $course->id)
            ->with('success', 'Bab berhasil diperbarui!');
    }

    /**
     * Hapus Bab
     */
    public function destroy(Course $course, Chapter $chapter)
    {
        // Hapus Bab
        // Note: Lessons di dalamnya otomatis terhapus jika di migration menggunakan onDelete('cascade')
        // Jika tidak, Anda bisa menambahkan $chapter->lessons()->delete(); di sini.
        $chapter->delete();

        return redirect()->route('admin.courses.chapters.index', $course->id)
            ->with('success', 'Bab berhasil dihapus!');
    }
}