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
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        $type = request()->query('type', 'video');
        return view('admin.lessons.create', compact('chapter', 'course', 'chapters', 'type'));
    }

    /**
     * SIMPAN MATERI BARU (STORE)
     */
    public function store(Request $request, Chapter $chapter)
    {
        // 1. Validasi Input (Dipisah ke fungsi private di bawah)
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            // 2. Siapkan & Simpan Data Lesson
            $data = $this->prepareLessonData($request, $chapter->id);
            $lesson = Lesson::create($data);

            // 3. Simpan Soal dengan Kalkulasi Bobot Otomatis & Presisi
            if ($request->type == 'assignment' && !empty($request->questions)) {
                $this->calculateAndSaveQuestions($lesson, $request->questions);
            }

            DB::commit();

            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi berhasil dibuat! Nilai soal dihitung otomatis agar total 100.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file jika terlanjur upload tapi gagal simpan DB
            if (isset($data['file_path']) && $data['file_path']) {
                Storage::disk('public')->delete($data['file_path']);
            }
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Form edit materi
     */
    public function edit(Chapter $chapter, Lesson $lesson)
    {
        $course = $chapter->course;
        $chapters = $course->chapters()->with('lessons')->orderBy('id', 'asc')->get();
        
        // Load soal agar muncul di form edit
        $questions = $lesson->questions()->orderBy('created_at', 'asc')->get();

        return view('admin.lessons.edit', compact('chapter', 'lesson', 'course', 'chapters', 'questions'));
    }

    /**
     * UPDATE MATERI
     */
    public function update(Request $request, Chapter $chapter, Lesson $lesson)
    {
        $this->validateRequest($request);

        try {
            DB::beginTransaction();

            // 1. Update Data Lesson
            $data = $this->prepareLessonData($request, $chapter->id, $lesson);
            $lesson->update($data);

            // 2. Reset Soal Lama (Hapus semua soal sebelumnya)
            // Ini penting agar kalkulasi bobot ulang berjalan bersih
            $lesson->questions()->delete(); 

            // 3. Simpan Soal Baru dengan Kalkulasi Bobot Otomatis & Presisi
            if ($request->type == 'assignment' && !empty($request->questions)) {
                $this->calculateAndSaveQuestions($lesson, $request->questions);
            }

            DB::commit();
            return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
                ->with('success', 'Materi diperbarui! Nilai soal dihitung ulang agar total 100.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus materi
     */
    public function destroy(Chapter $chapter, Lesson $lesson)
    {
        if ($lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }
        $lesson->delete();
        
        return redirect()->route('admin.courses.chapters.index', $chapter->course_id)
            ->with('success', 'Materi berhasil dihapus!');
    }

    // =========================================================================
    // PRIVATE HELPER FUNCTIONS (LOGIKA UTAMA)
    // =========================================================================

    /**
     * Validasi Request
     */
    private function validateRequest($request) 
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type'  => 'required|in:video,text,pdf,assignment',
        ];

        // Validasi Konten berdasarkan Tipe
        if ($request->type == 'text' || $request->type == 'assignment') {
            $rules['content'] = 'required|string';
        } elseif ($request->type == 'video') {
            // [PERBAIKAN] Validasi deskripsi dipindah ke sini
            $rules['video_description'] = 'nullable|string'; 

            if ($request->video_source == 'upload') {
                $rules['video_file'] = 'nullable|file|mimes:mp4,mov,avi|max:102400'; 
                // Note: nullable di sini karena saat update mungkin tidak upload baru
                if ($request->isMethod('post')) $rules['video_file'] = 'required|file|mimes:mp4,mov,avi|max:102400';
            } elseif ($request->video_source == 'youtube') {
                $rules['video_url'] = 'required|string';
            }
        } elseif ($request->type == 'pdf') {
            $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:20480';
            if ($request->isMethod('post')) $rules['pdf_file'] = 'required|file|mimes:pdf|max:20480';
        }

        // Validasi Array Soal
        if ($request->type == 'assignment') {
            $rules['questions'] = 'nullable|array';
            $rules['questions.*.text'] = 'required|string';
        }

        $request->validate($rules);
    }

    /**
     * Siapkan Data Lesson (Handle Upload File)
     */
    private function prepareLessonData($request, $chapterId, $lesson = null) 
    {
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'type' => $request->type,
            'is_preview' => $request->has('is_preview'),
            'show_results' => $request->has('show_results'),
        ];

        // Hanya set chapter_id jika ini create (lesson null)
        if (!$lesson) {
            $data['chapter_id'] = $chapterId;
        }

        // Logic Konten & File Upload
        if ($request->type == 'text' || $request->type == 'assignment') {
            $data['content'] = $request->content;
            
            // Jika ganti tipe dari video/pdf ke text, hapus file lama
            if ($lesson && $lesson->file_path) {
                // Opsional: Hapus file fisik
                // Storage::disk('public')->delete($lesson->file_path);
                $data['file_path'] = null;
                $data['video_source'] = null;
            }

        } elseif ($request->type == 'video') {
            // [PERBAIKAN] Langsung simpan data, tidak perlu mendefinisikan $rules di sini
            $data['content'] = $request->video_description ?? null;
            $data['video_source'] = $request->video_source;
            
            if ($request->video_source == 'upload' && $request->hasFile('video_file')) {
                // Hapus file lama jika ada
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
                // Hapus file lama jika ada
                if ($lesson && $lesson->file_path) {
                    Storage::disk('public')->delete($lesson->file_path);
                }
                $data['file_path'] = $request->file('pdf_file')->store('lessons/pdfs', 'public');
            }
        }

        return $data;
    }

    /**
     * IMPROVISASI LOGIKA PEMBAGIAN NILAI (PG Only / Campuran)
     * Memastikan total selalu 100 walaupun jumlah soal ganjil.
     */
    private function calculateAndSaveQuestions($lesson, $questionsData)
    {
        $maxScore = 100;
        $totalEssayPoints = 0;
        $pgCount = 0;

        // 1. Hitung total poin Essay & Jumlah soal PG
        foreach ($questionsData as $q) {
            if (empty($q['text'])) continue;
            
            if ($q['type'] == 'essay') {
                // Ambil bobot manual dari input user untuk essay
                $totalEssayPoints += intval($q['points'] ?? 0);
            } elseif ($q['type'] == 'multiple_choice') {
                // Hitung jumlah soal PG
                $pgCount++;
            }
        }

        // 2. Hitung Sisa Nilai untuk PG
        // Jika HANYA PG: $totalEssayPoints = 0, jadi $remainingScore = 100
        // Jika CAMPURAN: 100 - Total Essay
        $remainingScore = max(0, $maxScore - $totalEssayPoints);
        
        // 3. Logika Pembagian Sisa Bagi (Modulo Distribution)
        // Agar total pas 100 jika pembagian menghasilkan desimal (misal 100 / 3)
        // Contoh: 100 poin / 3 soal. 
        // Base = 33. Sisa = 1.
        // Distribusi: 34, 33, 33. Total = 100.
        
        $basePgScore = ($pgCount > 0) ? floor($remainingScore / $pgCount) : 0;
        $remainder = ($pgCount > 0) ? ($remainingScore % $pgCount) : 0;

        // Counter untuk mendistribusikan sisa nilai ke soal-soal awal
        $distributedCount = 0;

        // 4. Simpan ke Database
        foreach ($questionsData as $qData) {
            if (empty($qData['text'])) continue;

            $question = new Question();
            $question->lesson_id = $lesson->id;
            $question->question_text = $qData['text'];
            $question->type = $qData['type'];
            $question->correct_answer = $qData['correct_answer'] ?? null;

            // --- APLIKASI NILAI ---
            if ($qData['type'] == 'multiple_choice') {
                // Tambahkan 1 poin ekstra ke sejumlah soal sebanyak sisa bagi ($remainder)
                $extraPoint = ($distributedCount < $remainder) ? 1 : 0;
                
                $question->points = $basePgScore + $extraPoint;
                
                $distributedCount++; // Tandai bahwa 1 soal PG sudah diproses
            } else {
                // Jika Essay, gunakan nilai input manual admin
                $question->points = intval($qData['points'] ?? 0); 
            }

            // Simpan Opsi Jawaban (Array -> JSON via Model Casts)
            if ($qData['type'] == 'multiple_choice' && isset($qData['options'])) {
                $question->options = $qData['options'];
            }

            $question->save();
        }
    }
}