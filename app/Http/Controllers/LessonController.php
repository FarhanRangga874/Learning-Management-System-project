<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Tampilkan daftar materi dalam bab
     */
    public function index(Chapter $chapter)
    {
        $lessons = $chapter->lessons()->orderBy('sort_order', 'asc')->get();
        return view('admin.lessons.index', compact('chapter', 'lessons'));
    }

    /**
     * Form tambah materi
     */
    public function create(Chapter $chapter)
    {
        return view('admin.lessons.create', compact('chapter'));
    }

    /**
     * Simpan materi (Store)
     */
    public function store(Request $request, Chapter $chapter)
    {
        // 1. Definisikan aturan dasar
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf',
        ];

        // 2. Validasi Dinamis sesuai tipe
        if ($request->type == 'text') {
            $rules['content'] = 'required|string';
        } 
        elseif ($request->type == 'video') {
            $rules['video_source'] = 'required|in:upload,youtube';
            
            if ($request->video_source == 'upload') {
                $rules['video_file'] = 'required|file|mimes:mp4,mov,avi,mkv|max:102400'; // Max 100MB
            } elseif ($request->video_source == 'youtube') {
                $rules['video_url'] = 'required|string';
            }
        }
        elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:20480'; // Max 20MB
        }

        $request->validate($rules);

        // 3. Siapkan data dasar
        $data = [
            'chapter_id' => $chapter->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'type' => $request->type,
        ];

        // 4. Logika Penyimpanan ke kolom 'file_path'
        if ($request->type == 'text') {
            $data['content'] = $request->content;
            $data['video_source'] = null;
            $data['file_path'] = null;
        } 
        elseif ($request->type == 'video') {
            $data['content'] = ''; 
            $data['video_source'] = $request->video_source;

            if ($request->video_source == 'upload' && $request->hasFile('video_file')) {
                // Simpan Video
                $path = $request->file('video_file')->store('lessons/videos', 'public');
                $data['file_path'] = $path;
            } 
            elseif ($request->video_source == 'youtube') {
                // Simpan URL Youtube
                $data['file_path'] = $request->video_url;
            }
        }
        elseif ($request->type == 'pdf') {
            $data['content'] = '';
            $data['video_source'] = 'upload'; // PDF dianggap sebagai file upload
            
            if ($request->hasFile('pdf_file')) {
                // Simpan PDF
                $path = $request->file('pdf_file')->store('lessons/pdfs', 'public');
                $data['file_path'] = $path; 
            }
        }
        
        Lesson::create($data);

        return redirect()->route('admin.chapters.lessons.index', $chapter->id)
            ->with('success', 'Materi berhasil dibuat!');
    }

    /**
     * Form edit materi
     */
    public function edit(Chapter $chapter, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('chapter', 'lesson'));
    }

    /**
     * Update materi
     */
    public function update(Request $request, Chapter $chapter, Lesson $lesson)
    {
        // 1. Aturan Dasar
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf',
        ];

        // 2. Validasi Dinamis (Update)
        if ($request->type == 'text') {
            $rules['content'] = 'required|string';
        } 
        elseif ($request->type == 'video') {
            $rules['video_source'] = 'required|in:upload,youtube';
            
            if ($request->video_source == 'upload') {
                $rules['video_file'] = 'nullable|file|mimes:mp4,mov,avi,mkv|max:102400';
            } elseif ($request->video_source == 'youtube') {
                $rules['video_url'] = 'required|string';
            }
        }
        elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:20480';
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'type' => $request->type,
        ];

        // 3. Logika Hapus File Lama (Cleanup)
        // Jika file sebelumnya adalah 'upload' (video/pdf) dan ada path-nya
        if ($lesson->video_source == 'upload' && $lesson->file_path) {
            // Hapus jika: Tipe berubah ATAU ada file baru yang diupload
            if ($request->type != $lesson->type || $request->hasFile('video_file') || $request->hasFile('pdf_file')) {
                Storage::disk('public')->delete($lesson->file_path);
            }
        }

        // 4. Logika Update
        if ($request->type == 'text') {
            $data['content'] = $request->content;
            $data['video_source'] = null;
            $data['file_path'] = null;
        } 
        elseif ($request->type == 'video') {
            $data['content'] = '';
            $data['video_source'] = $request->video_source;

            if ($request->video_source == 'upload') {
                if ($request->hasFile('video_file')) {
                    $path = $request->file('video_file')->store('lessons/videos', 'public');
                    $data['file_path'] = $path;
                } else {
                    // Pakai path lama jika tidak ada file baru
                    if ($lesson->type == 'video' && $lesson->video_source == 'upload') {
                        $data['file_path'] = $lesson->file_path;
                    }
                }
            } 
            elseif ($request->video_source == 'youtube') {
                $data['file_path'] = $request->video_url;
            }
        }
        elseif ($request->type == 'pdf') {
            $data['content'] = '';
            $data['video_source'] = 'upload';

            if ($request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('lessons/pdfs', 'public');
                $data['file_path'] = $path;
            } else {
                // Pakai path lama jika tidak ada file baru
                if ($lesson->type == 'pdf') {
                    $data['file_path'] = $lesson->file_path;
                }
            }
        }
        
        $lesson->update($data);

        return redirect()->route('admin.chapters.lessons.index', $chapter->id)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Hapus materi
     */
    public function destroy(Chapter $chapter, Lesson $lesson)
    {
        // Hapus file fisik jika ada (Berlaku untuk Video Upload & PDF)
        if ($lesson->video_source == 'upload' && $lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }

        $lesson->delete();
        
        return redirect()->route('admin.chapters.lessons.index', $chapter->id)
            ->with('success', 'Materi berhasil dihapus!');
    }
}