<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use Illuminate\Routing\Controller;
use App\Models\User; 
use App\Models\Lesson;
use Carbon\Carbon;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Set Bahasa Indonesia
        Carbon::setLocale('id'); 
        
        $range = $request->input('range', 'month');
        $startDate = now();
        $dateFormat = 'Y-m-d'; // Default per hari

        // Tentukan rentang waktu & format grouping chart
        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $dateFormat = 'H:00'; // Grouping per Jam
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $dateFormat = 'Y-m-d'; // Grouping per Hari
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $dateFormat = 'Y-m-d'; // Grouping per Hari
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $dateFormat = 'Y-m'; // Grouping per Bulan
                break;
            case 'all':
                $startDate = Carbon::create(2000, 1, 1);
                $dateFormat = 'Y-m'; // Grouping per Bulan (Keseluruhan)
                break;
        }

        // 2. Ambil Data Mentah untuk Chart Trend Pendaftaran
        $rawTrend = \App\Models\Enrollment::select(
                DB::raw("DATE_FORMAT(joined_at, '$dateFormat') as date"), 
                DB::raw('count(*) as count')
            )
            ->where('joined_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Format Label Chart Trend
        $enrollmentTrend = $rawTrend->map(function($item) use ($range) {
            try {
                if ($range == 'today') {
                    // Contoh: Jam 08:00
                    $item->label = 'Jam ' . $item->date; 
                } elseif ($range == 'year' || $range == 'all') {
                    // Contoh: Januari 2025
                    $item->label = Carbon::createFromFormat('Y-m', $item->date)->translatedFormat('F Y'); 
                } else {
                    // Contoh: Senin, 20 Jan
                    $item->label = Carbon::parse($item->date)->translatedFormat('l, d M');
                }
            } catch (\Exception $e) {
                $item->label = $item->date;
            }
            return $item;
        });

        // 4. Query Tabel Rekap Top Kursus
        $recapCourses = Course::withCount(['students as recent_students_count' => function ($query) use ($startDate) {
            $query->where('enrollments.joined_at', '>=', $startDate);
        }])
        ->having('recent_students_count', '>', 0)
        ->orderByDesc('recent_students_count')
        ->take(10)
        ->get();

        $totalEnrollmentsInPeriod = $recapCourses->sum('recent_students_count');

        // ==========================================================
        // 5. REKAP KATEGORI (Chart & Stats)
        // ==========================================================
        
        // A. Data Lengkap untuk Chart Interaktif (FIXED: Sekarang mengikuti Range Waktu)
        // Menggunakan Model Category agar konsisten dengan Eloquent
        $chartData = Category::leftJoin('courses', 'categories.id', '=', 'courses.category_id')
            ->leftJoin('enrollments', function($join) use ($startDate) {
                $join->on('courses.id', '=', 'enrollments.course_id')
                     ->where('enrollments.joined_at', '>=', $startDate); // Filter Waktu Siswa
            })
            ->leftJoin('certificates', function($join) use ($startDate) {
                $join->on('courses.id', '=', 'certificates.course_id')
                     ->where('certificates.created_at', '>=', $startDate); // Filter Waktu Sertifikat
            })
            ->select(
                'categories.name',
                // Tetap hitung total kursus (Inventory biasanya tidak kena filter waktu pendaftaran)
                DB::raw('COUNT(DISTINCT courses.id) as courses_count'),
                // Hitung siswa & sertifikat sesuai filter waktu
                DB::raw('COUNT(DISTINCT enrollments.id) as students_count'),
                DB::raw('COUNT(DISTINCT certificates.id) as certificates_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // B. Data untuk Tabel Statistik (Pagination)
        $categoryStats = Category::leftJoin('courses', 'categories.id', '=', 'courses.category_id')
            ->leftJoin('enrollments', function($join) use ($startDate) {
                $join->on('courses.id', '=', 'enrollments.course_id')
                     ->where('enrollments.joined_at', '>=', $startDate);
            })
            ->leftJoin('certificates', function($join) use ($startDate) {
                $join->on('courses.id', '=', 'certificates.course_id')
                     ->where('certificates.created_at', '>=', $startDate);
            })
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT courses.id) as courses_count'),
                DB::raw('COUNT(DISTINCT enrollments.id) as students_count'),
                DB::raw('COUNT(DISTINCT certificates.id) as certificates_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('students_count')
            ->paginate(5, ['*'], 'cat_page');

        // ==========================================================
        // 6. QUERY UTAMA LIST KURSUS & LAPORAN
        // ==========================================================
        
        $query = Course::with('category')
            ->withCount(['students', 'certificates']) 
            ->with(['lessons.questions.userAnswers']) 
            ->orderBy('id', 'desc');

        if ($request->has('search')) {
            $query->where('title', 'LIKE', "%{$request->search}%");
        }

        $courses = $query->paginate(10); 

        // Variable bantu untuk perhitungan rata-rata global
        $globalTotalScore = 0;
        $globalTotalMaxScore = 0;
        $globalCompletionRateTotal = 0;
        $activeCoursesCount = 0; // Kursus yang memiliki siswa

        // Transformasi data untuk statistik per kursus
        $courses->getCollection()->transform(function ($course) use (&$globalTotalScore, &$globalTotalMaxScore, &$globalCompletionRateTotal, &$activeCoursesCount) {
            
            // Hitung Completion Rate per kursus
            $completionRate = $course->students_count > 0 
                ? round(($course->certificates_count / $course->students_count) * 100) 
                : 0;
            
            $course->completion_rate = $completionRate;

            // Tambahkan ke total global jika ada siswa
            if ($course->students_count > 0) {
                $globalCompletionRateTotal += $completionRate;
                $activeCoursesCount++;
            }

            // Hitung Average Score per kursus
            $totalEarnedScore = 0;
            $totalMaxScore = 0;

            foreach ($course->lessons as $lesson) {
                foreach ($lesson->questions as $question) {
                    $answers = $question->userAnswers;
                    if ($answers->isNotEmpty()) {
                        $totalEarnedScore += $answers->sum('score');
                        $totalMaxScore += ($question->points * $answers->count());
                    }
                }
            }

            $course->average_score = $totalMaxScore > 0 
                ? round(($totalEarnedScore / $totalMaxScore) * 100) 
                : 0;

            // Akumulasi skor global
            $globalTotalScore += $totalEarnedScore;
            $globalTotalMaxScore += $totalMaxScore;

            return $course;
        });

        // ==========================================================
        // 7. HITUNG STATISTIK GLOBAL (CARD BARU)
        // ==========================================================
        
        // A. Rata-rata Kelulusan Global
        // (Rata-rata dari persentase kelulusan semua kursus aktif)
        $averageCompletionRate = $activeCoursesCount > 0 
            ? round($globalCompletionRateTotal / $activeCoursesCount) 
            : 0;

        // B. Total Nilai Rata-rata Global
        // (Total skor yang didapat semua user di semua kursus / Total skor maksimal yang mungkin didapat)
        $overallAverageScore = $globalTotalMaxScore > 0 
            ? round(($globalTotalScore / $globalTotalMaxScore) * 100) 
            : 0;

        // ==========================================================
        
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalCourses = Course::count();

        // Jika request AJAX (dari klik pagination/filter), return partial view
        if ($request->ajax()) {
            return view('admin.courses.index', compact(
                'courses', 'totalUsers', 'totalCourses', 
                'range', 'enrollmentTrend', 'recapCourses', 'totalEnrollmentsInPeriod',
                'categoryStats', 'chartData',
                'averageCompletionRate', 'overallAverageScore' // <-- Kirim variable baru
            ))->render(); // Render full view tapi nanti JS ambil bagian #main-ajax-wrapper saja
        }

        return view('admin.courses.index', compact(
            'courses', 'totalUsers', 'totalCourses', 
            'range', 'enrollmentTrend', 'recapCourses', 'totalEnrollmentsInPeriod',
            'categoryStats', 'chartData',
            'averageCompletionRate', 'overallAverageScore' // <-- Kirim variable baru
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'access_type' => 'required|in:open,code',
            'access_code' => 'nullable|string',
            'certificate_policy' => 'required|in:manual,auto', 
            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            
            $validated = $request->only([
                'title', 
                'category_id', 
                'description', 
                'access_type', 
                'access_code',
                'certificate_policy'
            ]);
            
            $validated['slug'] = Str::slug($request->title);

            if ($request->hasFile('thumbnail')) {
                $iconPath = $request->file('thumbnail')->store('courses', 'public');
                $validated['thumbnail'] = $iconPath;
            }
            
            // 1. Simpan Course
            $course = Course::create($validated);

            // 2. Simpan Keypoints
            if ($request->has('course_keypoints')) {
                foreach ($request->course_keypoints as $keypoint) {
                    if (!empty($keypoint)) {
                        $course->keypoints()->create([
                            'name' => $keypoint
                        ]);
                    }
                }
            }

        });

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $students = $course->students()->orderByPivot('joined_at', 'desc')->paginate(10);
        $course->load('keypoints'); 
        
        return view('admin.courses.show', compact('course', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $categories = Category::all();
        $course->load('keypoints'); 
        
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'access_type' => 'required|in:open,code',
            'access_code' => 'nullable|string',
            'certificate_policy' => 'required|in:manual,auto',
            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $course) {
            
            $validated = $request->only([
                'title', 
                'category_id', 
                'description', 
                'access_type', 
                'access_code',
                'certificate_policy' 
            ]);
            
            $validated['slug'] = Str::slug($request->title);

            if ($request->hasFile('thumbnail')) {
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $iconPath = $request->file('thumbnail')->store('courses', 'public');
                $validated['thumbnail'] = $iconPath;
            }

            // 1. Update Course
            $course->update($validated);

            // 2. Update Keypoints
            if ($request->has('course_keypoints')) {
                $course->keypoints()->delete(); 

                foreach ($request->course_keypoints as $keypoint) {
                    if (!empty($keypoint)) {
                        $course->keypoints()->create([
                            'name' => $keypoint
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        DB::transaction(function () use ($course) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->delete();
        });
        
        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dihapus!');
    }

    public function assignments(Course $course)
    {
        // 1. Load Relasi yang Dibutuhkan untuk Statistik
        $course->loadCount(['students', 'certificates']);
        $course->load(['lessons.questions.userAnswers']); // Load jawaban untuk hitung skor

        // 2. Hitung Completion Rate
        $course->completion_rate = $course->students_count > 0 
            ? round(($course->certificates_count / $course->students_count) * 100) 
            : 0;

        // 3. Hitung Average Score (Akurat: Berdasarkan Poin)
        $totalEarnedScore = 0;
        $totalMaxScore = 0;

        foreach ($course->lessons as $lesson) {
            foreach ($lesson->questions as $question) {
                $answers = $question->userAnswers;
                if ($answers->isNotEmpty()) {
                    $totalEarnedScore += $answers->sum('score');
                    $totalMaxScore += ($question->points * $answers->count());
                }
            }
        }

        $course->average_score = $totalMaxScore > 0 
            ? round(($totalEarnedScore / $totalMaxScore) * 100) 
            : 0;

        // 4. Ambil Data Assignment & Hitung Pending Review
        $assignments = Lesson::whereHas('chapter', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->where('type', 'assignment')->with('chapter')->get();

        foreach($assignments as $assignment) {
            // Hitung user yang jawabannya belum dinilai (score 0)
            $pendingCount = \App\Models\User::whereHas('answers', function($q) use ($assignment) {
                $q->whereHas('question', function($subQ) use ($assignment) {
                    $subQ->where('lesson_id', $assignment->id);
                })->where('score', 0); 
            })->count();

            $assignment->pending_count = $pendingCount;
        }

        return view('admin.courses.assignments', compact('course', 'assignments'));
    }
}