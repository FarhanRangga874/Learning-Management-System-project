<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // PENTING: Tambahkan ini untuk Transaction
use Illuminate\Routing\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Perbaikan: Kode sebelumnya ada 2 return, yang bawah tidak tereksekusi.
        // Kita gabungkan jadi satu query yang lengkap.
        $courses = Course::with('category')
            ->withCount('students') // Menghitung jumlah siswa
            ->orderBy('id', 'desc')
            ->get();
            
        return view('admin.courses.index', compact('courses'));
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
            // VALIDASI BARU: Keypoints (Array)
            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        // Gunakan DB Transaction agar data konsisten
        // (Jika simpan keypoints gagal, course tidak akan tersimpan)
        DB::transaction(function () use ($request) {
            
            $validated = $request->only(['title', 'category_id', 'description', 'access_type', 'access_code']);
            $validated['slug'] = Str::slug($request->title);

            if ($request->hasFile('thumbnail')) {
                $iconPath = $request->file('thumbnail')->store('courses', 'public');
                $validated['thumbnail'] = $iconPath;
            }
            
            // 1. Simpan Course Utama
            $course = Course::create($validated);

            // 2. Simpan Keypoints (Tujuan Pembelajaran)
            if ($request->has('course_keypoints')) {
                foreach ($request->course_keypoints as $keypoint) {
                    // Hanya simpan jika tidak kosong
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
        // Load relationship for enrolled students, ordered by enrollment date
        // Assuming the pivot table is named 'course_student' or 'enrollments' and has timestamps
        $students = $course->students()->orderByPivot('joined_at', 'desc')->paginate(10);
        
        // Load keypoints juga jika ingin ditampilkan di admin
        $course->load('keypoints'); 
        
        return view('admin.courses.show', compact('course', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $categories = Category::all();
        // Load keypoints agar bisa ditampilkan di form edit
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
            // VALIDASI BARU
            'course_keypoints' => 'nullable|array',
            'course_keypoints.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $course) {
            
            $validated = $request->only(['title', 'category_id', 'description', 'access_type', 'access_code']);
            $validated['slug'] = Str::slug($request->title);

            if ($request->hasFile('thumbnail')) {
                // Hapus gambar lama
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                // Simpan baru
                $iconPath = $request->file('thumbnail')->store('courses', 'public');
                $validated['thumbnail'] = $iconPath;
            }

            // 1. Update Course Utama
            $course->update($validated);

            // 2. Update Keypoints
            // Strategi: Hapus semua keypoints lama, lalu buat ulang sesuai inputan baru.
            // Ini menangani kasus edit teks, hapus poin, dan tambah poin sekaligus.
            if ($request->has('course_keypoints')) {
                $course->keypoints()->delete(); // Hapus data lama di database

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
            // Hapus gambar fisik
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            // Keypoints akan otomatis terhapus karena 'onDelete cascade' di migration
            // Tapi untuk keamanan, kita bisa delete manual course-nya
            $course->delete();
        });
        
        return redirect()->route('admin.courses.index')->with('success', 'Kursus berhasil dihapus!');
    }
}