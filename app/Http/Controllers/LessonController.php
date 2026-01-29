<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Question; // Pastikan Model Question di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB; // Wajib untuk Transaction
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Tampilkan daftar materi
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
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        return view('admin.lessons.create', compact('chapter', 'course', 'chapters'));
    }

    /**
     * Simpan materi (Dan soal jika ada)
     */
    public function store(Request $request, Chapter $chapter)
    {
        // 1. Validasi Input
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        // Validasi Konten berdasarkan Tipe
        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string'; // Deskripsi tugas / isi artikel
        } elseif ($request->type == 'video' && $request->video_source == 'upload') {
            $rules['video_file'] = 'required|file|mimes:mp4,mov,avi|max:102400';
        } elseif ($request->type == 'video' && $request->video_source == 'youtube') {
            $rules['video_url'] = 'required|string';
        } elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:20480';
        }

        // Validasi Array Soal (Khusus jika tipe Assignment)
        if ($request->type == 'assignment') {
            $rules['questions'] = 'nullable|array';
            $rules['questions.*.text'] = 'required|string'; // Pertanyaan wajib diisi
            $rules['questions.*.type'] = 'required|in:multiple_choice,essay';
            $rules['questions.*.points'] = 'required|integer|min:1';
        }

        $request->validate($rules);

        // 2. Mulai Transaction (Simpan Lesson + Questions sekaligus)
        try {
            DB::beginTransaction();

            // A. Siapkan Data Lesson
            $data = [
                'chapter_id' => $chapter->id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::random(5),
                'type' => $request->type,
                'is_preview' => $request->has('is_preview') ? true : false,
            ];

            // B. Handle File Upload / Content
            if ($request->type == 'text' || $request->type == 'assignment') {
                $data['content'] = $request->content;
                $data['video_source'] = null;
                $data['file_path'] = null;
            } elseif ($request->type == 'video') {
                $data['content'] = ''; 
                $data['video_source'] = $request->video_source;
                if ($request->video_source == 'upload' && $request->hasFile('video_file')) {
                    $data['file_path'] = $request->file('video_file')->store('lessons/videos', 'public');
                } elseif ($request->video_source == 'youtube') {
                    $data['file_path'] = $request->video_url;
                }
            } elseif ($request->type == 'pdf') {
                $data['content'] = '';
                $data['video_source'] = 'upload';
                if ($request->hasFile('pdf_file')) {
                    $data['file_path'] = $request->file('pdf_file')->store('lessons/pdfs', 'public');
                }
            }

            // C. Create Lesson
            $lesson = Lesson::create($data);

            // D. Loop Simpan Soal (Questions) jika ada
            if ($request->type == 'assignment' && !empty($request->questions)) {
                
                foreach ($request->questions as $qData) {
                    // Skip jika text kosong
                    if (empty($qData['text'])) continue;

                    // Create Question Record
                    $question = $lesson->questions()->create([
                        'question_text' => $qData['text'],
                        'type' => $qData['type'],
                        'points' => $qData['points'] ?? 10,
                        'correct_answer' => $qData['correct_answer'] ?? null, // 'A', 'B', etc.
                    ]);

                    // Update Opsi Jawaban (Khusus PG)
                    // Asumsi: Model Question punya kolom 'answers' yang di-cast ke array
                    if ($qData['type'] == 'multiple_choice' && isset($qData['options'])) {
                        $question->update([
                            'answers' => $qData['options'] // Array ['A'=>'...', 'B'=>'...']
                        ]);
                    }
                }
            }

            DB::commit(); // Commit database jika semua sukses

            // Redirect Sukses
            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi dan Soal berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika error
            
            // Hapus file fisik jika terlanjur ke-upload
            if (isset($data['file_path']) && $data['file_path']) {
                Storage::disk('public')->delete($data['file_path']);
            }

            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Form edit materi
     */
    public function edit(Chapter $chapter, Lesson $lesson)
    {
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        
        // Ambil soal untuk ditampilkan di form edit (agar bisa dikelola lagi nanti)
        $questions = $lesson->questions()->orderBy('created_at', 'asc')->get();

        return view('admin.lessons.edit', compact('chapter', 'lesson', 'course', 'chapters', 'questions'));
    }

    /**
     * Update materi
     */
    public function update(Request $request, Chapter $chapter, Lesson $lesson)
    {
        // ... (Logika Update sama seperti sebelumnya) ...
        // Note: Biasanya update soal dilakukan secara terpisah atau butuh logika sinkronisasi ID yang lebih kompleks.
        // Di sini saya fokuskan update hanya untuk properti Lesson-nya saja.

        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string';
        } elseif ($request->type == 'video') {
            $rules['video_source'] = 'required|in:upload,youtube';
            if ($request->video_source == 'upload') {
                $rules['video_file'] = 'nullable|file|mimes:mp4,mov,avi,mkv|max:102400';
            } elseif ($request->video_source == 'youtube') {
                $rules['video_url'] = 'required|string';
            }
        } elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:20480';
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'type' => $request->type,
            'is_preview' => $request->has('is_preview') ? true : false,
        ];

        // Hapus file lama jika perlu
        if ($lesson->file_path && ($request->type != $lesson->type || $request->hasFile('video_file') || $request->hasFile('pdf_file'))) {
            if ($lesson->video_source == 'upload' || $lesson->type == 'pdf') {
                Storage::disk('public')->delete($lesson->file_path);
            }
        }

        // Logic Update File/Content
        if ($request->type == 'text' || $request->type == 'assignment') {
            $data['content'] = $request->content;
            $data['video_source'] = null;
            $data['file_path'] = null;
        } elseif ($request->type == 'video') {
            $data['content'] = '';
            $data['video_source'] = $request->video_source;
            if ($request->video_source == 'upload') {
                if ($request->hasFile('video_file')) {
                    $path = $request->file('video_file')->store('lessons/videos', 'public');
                    $data['file_path'] = $path;
                }
            } elseif ($request->video_source == 'youtube') {
                $data['file_path'] = $request->video_url;
            }
        } elseif ($request->type == 'pdf') {
            $data['content'] = '';
            $data['video_source'] = 'upload';
            if ($request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('lessons/pdfs', 'public');
                $data['file_path'] = $path;
            }
        }
        
        $lesson->update($data);

        // Jika assignment, redirect ke edit agar user bisa lanjut edit soal
        if ($lesson->type == 'assignment') {
            return redirect()->route('admin.chapters.lessons.edit', [$chapter->id, $lesson->id])
                ->with('success', 'Info tugas diperbarui. Silakan kelola soal di bawah.');
        }

        return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Hapus materi
     */
    public function destroy(Chapter $chapter, Lesson $lesson)
    {
        if ($lesson->file_path) {
            if ($lesson->video_source == 'upload' || $lesson->type == 'pdf') {
                Storage::disk('public')->delete($lesson->file_path);
            }
        }

        $lesson->delete();
        
        return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
            ->with('success', 'Materi berhasil dihapus!');
    }
}