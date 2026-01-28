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

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with('category')->withCount('students')->orderBy('id', 'desc');

        if ($request->has('search')) {
            $keyword = $request->input('search');
            $query->where('title', 'LIKE', "%{$keyword}%");
        }

        $courses = $query->get(); 

        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalCourses = Course::count();

        return view('admin.courses.index', compact('courses', 'totalUsers', 'totalCourses'));
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
}