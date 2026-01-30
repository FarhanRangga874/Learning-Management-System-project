<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function index(Chapter $chapter)
    {
        $lessons = $chapter->lessons()->orderBy('sort_order', 'asc')->get();
        return view('admin.lessons.index', compact('chapter', 'lessons'));
    }

    public function create(Chapter $chapter)
    {
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        $type = request()->query('type', 'video');
        return view('admin.lessons.create', compact('chapter', 'course', 'chapters', 'type'));
    }

    public function store(Request $request, Chapter $chapter)
    {
        // 1. Validasi
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        // Validasi Konten
        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string';
        } elseif ($request->type == 'video' && $request->video_source == 'upload') {
            $rules['video_file'] = 'required|file|mimes:mp4,mov,avi|max:102400';
        } elseif ($request->type == 'video' && $request->video_source == 'youtube') {
            $rules['video_url'] = 'required|string';
        } elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'required|file|mimes:pdf|max:20480';
        }

        // Validasi Soal
        if ($request->type == 'assignment') {
            $rules['questions'] = 'nullable|array';
            $rules['questions.*.text'] = 'required|string';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 2. Simpan Lesson
            $data = [
                'chapter_id' => $chapter->id,
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::random(5),
                'type' => $request->type,
                'is_preview' => $request->has('is_preview'),
            ];

            if ($request->type == 'text' || $request->type == 'assignment') {
                $data['content'] = $request->content;
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

            $lesson = Lesson::create($data);

            // 3. Simpan Soal
            if ($request->type == 'assignment' && !empty($request->questions)) {
                foreach ($request->questions as $qData) {
                    if (empty($qData['text'])) continue;

                    $question = new Question();
                    $question->lesson_id = $lesson->id;
                    $question->question_text = $qData['text'];
                    $question->type = $qData['type'];
                    $question->points = $qData['points'] ?? 10;
                    $question->correct_answer = $qData['correct_answer'] ?? null;
                    
                    if ($qData['type'] == 'multiple_choice' && isset($qData['options'])) {
                        $question->options = $qData['options'];
                    }

                    $question->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi dan Soal berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($data['file_path']) && $data['file_path']) Storage::disk('public')->delete($data['file_path']);
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function edit(Chapter $chapter, Lesson $lesson)
    {
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        
        // Load soal agar muncul di form edit
        $questions = $lesson->questions()->orderBy('created_at', 'asc')->get();

        return view('admin.lessons.edit', compact('chapter', 'lesson', 'course', 'chapters', 'questions'));
    }

public function update(Request $request, Chapter $chapter, Lesson $lesson)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string';
        }

        if ($request->type == 'assignment') {
            $rules['questions'] = 'nullable|array';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'type' => $request->type,
                'is_preview' => $request->has('is_preview'),
            ];

            // Logic Update Konten/File
            if ($request->type == 'text' || $request->type == 'assignment') {
                $data['content'] = $request->content;
            } elseif ($request->type == 'video') {
                $data['video_source'] = $request->video_source;
                if ($request->video_source == 'upload' && $request->hasFile('video_file')) {
                    if($lesson->file_path) Storage::disk('public')->delete($lesson->file_path);
                    $data['file_path'] = $request->file('video_file')->store('lessons/videos', 'public');
                } elseif ($request->video_source == 'youtube') {
                    $data['file_path'] = $request->video_url;
                }
            } elseif ($request->type == 'pdf') {
                if ($request->hasFile('pdf_file')) {
                    if($lesson->file_path) Storage::disk('public')->delete($lesson->file_path);
                    $data['file_path'] = $request->file('pdf_file')->store('lessons/pdfs', 'public');
                }
            }
            
            $lesson->update($data);

            // ============================================================
            // LOGIKA PENTING: RESET SOAL (HILANGKAN SOAL LAMA)
            // ============================================================
            
            // 1. Apapun tipenya, KITA HAPUS SEMUA SOAL LAMA terlebih dahulu.
            // Ini menjamin jika user ubah dari 'Tugas' ke 'Video', soal akan hilang.
            // Ini juga menjamin saat edit 'Tugas', kita tidak menumpuk soal lama.
            $lesson->questions()->delete(); 

            // 2. Jika tipe saat ini adalah Assignment, baru kita simpan soal baru dari form
            if ($request->type == 'assignment' && !empty($request->questions)) {
                foreach ($request->questions as $qData) {
                    // Skip jika teks pertanyaan kosong
                    if (empty($qData['text'])) continue;

                    $question = new Question();
                    $question->lesson_id = $lesson->id;
                    $question->question_text = $qData['text'];
                    $question->type = $qData['type'];
                    $question->points = $qData['points'] ?? 10;
                    $question->correct_answer = $qData['correct_answer'] ?? null;

                    if ($qData['type'] == 'multiple_choice' && isset($qData['options'])) {
                        $question->options = $qData['options'];
                    }

                    $question->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroy(Chapter $chapter, Lesson $lesson)
    {
        if ($lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }
        $lesson->delete();
        
        return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
            ->with('success', 'Materi berhasil dihapus!');
    }
}