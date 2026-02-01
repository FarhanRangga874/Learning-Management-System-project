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
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            $data = $this->prepareLessonData($request, $chapter->id);
            $lesson = Lesson::create($data);

            if ($request->type == 'assignment' && !empty($request->questions)) {
                $this->calculateAndSaveQuestions($lesson, $request->questions);
            }

            DB::commit();

            // [MODIFIKASI DISINI] Menambahkan created_lesson_id ke session
            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi berhasil dibuat!')
                ->with('created_lesson_id', $lesson->id);

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($data['file_path']) && $data['file_path']) {
                Storage::disk('public')->delete($data['file_path']);
            }
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function edit(Chapter $chapter, Lesson $lesson)
    {
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        $questions = $lesson->questions()->orderBy('created_at', 'asc')->get();

        return view('admin.lessons.edit', compact('chapter', 'lesson', 'course', 'chapters', 'questions'));
    }

    public function update(Request $request, Chapter $chapter, Lesson $lesson)
    {
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            $data = $this->prepareLessonData($request, $chapter->id, $lesson);
            $lesson->update($data);

            // Reset soal lama jika assignment, atau jika ganti tipe jadi bukan assignment
            if ($request->type == 'assignment') {
                $lesson->questions()->delete(); 
                if (!empty($request->questions)) {
                    $this->calculateAndSaveQuestions($lesson, $request->questions);
                }
            } else {
                $lesson->questions()->delete();
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

    // --- HELPER FUNCTIONS ---

    private function validateRequest($request) 
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string';
        } elseif ($request->type == 'video') {
            $rules['video_description'] = 'nullable|string';

            if ($request->video_source == 'upload') {
                $rules['video_file'] = 'nullable|file|mimes:mp4,mov,avi|max:102400';
                if ($request->isMethod('post')) $rules['video_file'] = 'required|file|mimes:mp4,mov,avi|max:102400';
            } elseif ($request->video_source == 'youtube') {
                $rules['video_url'] = 'required|string';
            }
        } elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:20480';
            if ($request->isMethod('post')) $rules['pdf_file'] = 'required|file|mimes:pdf|max:20480';
        }

        if ($request->type == 'assignment') {
            $rules['questions'] = 'nullable|array';
            $rules['questions.*.text'] = 'required|string';
        }

        $request->validate($rules);
    }

    private function prepareLessonData($request, $chapterId, $lesson = null) 
    {
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'type' => $request->type,
            'is_preview' => $request->has('is_preview'),
            'show_results' => $request->has('show_results'),
        ];

        if (!$lesson) {
            $data['chapter_id'] = $chapterId;
        }

        // Logic Content Mapping
        if ($request->type == 'text' || $request->type == 'assignment') {
            $data['content'] = $request->content;
            
            if ($lesson && $lesson->file_path) {
                $data['file_path'] = null;
                $data['video_source'] = null;
            }

        } elseif ($request->type == 'video') {
            $data['content'] = $request->video_description ?? null;
            $data['video_source'] = $request->video_source;
            
            if ($request->video_source == 'upload' && $request->hasFile('video_file')) {
                if ($lesson && $lesson->file_path && $lesson->video_source == 'upload') {
                    Storage::disk('public')->delete($lesson->file_path);
                }
                $data['file_path'] = $request->file('video_file')->store('lessons/videos', 'public');
            } elseif ($request->video_source == 'youtube') {
                $data['file_path'] = $request->video_url;
            }

        } elseif ($request->type == 'pdf') {
            $data['content'] = ''; 
            $data['video_source'] = 'upload';
            
            if ($request->hasFile('pdf_file')) {
                if ($lesson && $lesson->file_path) {
                    Storage::disk('public')->delete($lesson->file_path);
                }
                $data['file_path'] = $request->file('pdf_file')->store('lessons/pdfs', 'public');
            }
        }

        return $data;
    }

    private function calculateAndSaveQuestions($lesson, $questionsData)
    {
        $maxScore = 100;
        $totalEssayPoints = 0;
        $pgCount = 0;

        foreach ($questionsData as $q) {
            if (empty($q['text'])) continue;
            if ($q['type'] == 'essay') {
                $totalEssayPoints += intval($q['points'] ?? 0);
            } elseif ($q['type'] == 'multiple_choice') {
                $pgCount++;
            }
        }

        $remainingScore = max(0, $maxScore - $totalEssayPoints);
        $basePgScore = ($pgCount > 0) ? floor($remainingScore / $pgCount) : 0;
        $remainder = ($pgCount > 0) ? ($remainingScore % $pgCount) : 0;
        $distributedCount = 0;

        foreach ($questionsData as $qData) {
            if (empty($qData['text'])) continue;

            $question = new Question();
            $question->lesson_id = $lesson->id;
            $question->question_text = $qData['text'];
            $question->type = $qData['type'];
            $question->correct_answer = $qData['correct_answer'] ?? null;

            if ($qData['type'] == 'multiple_choice') {
                $extraPoint = ($distributedCount < $remainder) ? 1 : 0;
                $question->points = $basePgScore + $extraPoint;
                $distributedCount++;
            } else {
                $question->points = intval($qData['points'] ?? 0); 
            }

            if ($qData['type'] == 'multiple_choice' && isset($qData['options'])) {
                $question->options = $qData['options'];
            }

            $question->save();
        }
    }
}