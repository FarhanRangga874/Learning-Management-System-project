<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user();

        // 2. Ambil daftar kursus milik user
        // Kita urutkan berdasarkan 'enrollments.id' desc (terbaru masuk paling atas)
        // Ini lebih aman daripada 'created_at' jika Anda mematikan timestamps.
        $myCourses = $user->courses()
                        ->with('category')
                        ->orderBy('enrollments.id', 'desc') 
                        ->get();

        // 3. Kirim data ke view
        return view('dashboard', compact('myCourses'));
    }
}
