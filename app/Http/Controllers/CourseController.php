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
use App\Models\UserAnswer;
use App\Models\Enrollment;
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
    $dateFormat = 'Y-m-d'; 

    // Tentukan rentang waktu
    switch ($range) {
        case 'today':
            $startDate = now()->startOfDay();
            $dateFormat = 'H:00'; 
            break;
        case 'week':
            $startDate = now()->startOfWeek();
            break;
        case 'month':
            $startDate = now()->startOfMonth();
            break;
        case 'year':
            $startDate = now()->startOfYear();
            $dateFormat = 'Y-m'; 
            break;
        case 'all':
            $startDate = Carbon::create(2000, 1, 1);
            break;
    }

    // 2. Ambil Data Mentah (Format SQL: 2026-01-20)
    $rawTrend = \App\Models\Enrollment::select(
            DB::raw("DATE_FORMAT(joined_at, '$dateFormat') as date"), 
            DB::raw('count(*) as count')
        )
        ->where('joined_at', '>=', $startDate)
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // 3. PERCANTIK LABEL (Agar tampil: "Senin, 20 Jan")
    $enrollmentTrend = $rawTrend->map(function($item) use ($range) {
        try {
            if ($range == 'today') {
                $item->label = 'Jam ' . $item->date; // Contoh: Jam 14:00
            } elseif ($range == 'year') {
                $item->label = Carbon::createFromFormat('Y-m', $item->date)->translatedFormat('F Y'); // Contoh: Januari 2026
            } else {
                // INI YANG PENTING: Mengubah "2026-01-20" jadi "Senin, 20 Jan"
                $item->label = Carbon::parse($item->date)->translatedFormat('l, d M');
            }
        } catch (\Exception $e) {
            $item->label = $item->date;
        }
        return $item;
    });

    // ... (Sisa kode query courses dan recap lainnya tetap sama) ...
    
    // Query Tabel Rekap Top Kursus
    $recapCourses = Course::withCount(['students as recent_students_count' => function ($query) use ($startDate) {
        $query->where('enrollments.joined_at', '>=', $startDate);
    }])
    ->having('recent_students_count', '>', 0)
    ->orderByDesc('recent_students_count')
    ->take(10)
    ->get();

    $totalEnrollmentsInPeriod = $recapCourses->sum('recent_students_count');

    // Query Utama (Pencarian & List Course)
    $query = Course::with('category')->withCount('students')->orderBy('id', 'desc');
    if ($request->has('search')) {
        $query->where('title', 'LIKE', "%{$request->search}%");
    }
    $courses = $query->get();
    
    $totalUsers = User::where('role', '!=', 'admin')->count();
    $totalCourses = Course::count();

    return view('admin.courses.index', compact(
        'courses', 'totalUsers', 'totalCourses', 
        'range', 'enrollmentTrend', 'recapCourses', 'totalEnrollmentsInPeriod'
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
            
            // --- PERBAIKAN 1: VALIDASI SERTIFIKAT ---
            'certificate_policy' => 'required|in:manual,auto', 
            
            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            
            // --- PERBAIKAN 2: MASUKKAN KE DATA YANG DISIMPAN ---
            $validated = $request->only([
                'title', 
                'category_id', 
                'description', 
                'access_type', 
                'access_code',
                'certificate_policy' // <--- PENTING: Agar opsi 'auto' tersimpan
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
            
            // --- PERBAIKAN 1: VALIDASI SERTIFIKAT (UPDATE) ---
            'certificate_policy' => 'required|in:manual,auto',

            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $course) {
            
            // --- PERBAIKAN 2: UPDATE DATA SERTIFIKAT ---
            $validated = $request->only([
                'title', 
                'category_id', 
                'description', 
                'access_type', 
                'access_code',
                'certificate_policy' // <--- PENTING: Agar perubahan tersimpan
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
        // Ambil assignment
        $assignments = Lesson::whereHas('chapter', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->where('type', 'assignment')->with('chapter')->get();

        // Hitung "Pending Grading" untuk setiap assignment
        foreach($assignments as $assignment) {
            // Cari jumlah User yang sudah jawab tapi total skornya masih 0 (atau logika lain sesuai kebutuhan)
            // Cara yang lebih akurat: Hitung UserAnswer yang skornya belum diisi (jika nullable) atau skor 0
            // Namun, karena di sistem kita skor default 0, kita bisa hitung user unik yang sudah jawab.
            
            // Logika: Kita butuh tahu berapa USER yang jawabannya belum kita sentuh.
            // Asumsi: Jika admin sudah menilai, skor > 0.
            // Jika belum menilai, skor = 0.
            
            $pendingCount = \App\Models\User::whereHas('answers', function($q) use ($assignment) {
                $q->whereHas('question', function($subQ) use ($assignment) {
                    $subQ->where('lesson_id', $assignment->id);
                })->where('score', 0); // Asumsi 0 = belum dinilai/salah total
            })->count();

            $assignment->pending_count = $pendingCount;
        }

        return view('admin.courses.assignments', compact('course', 'assignments'));
    }
}