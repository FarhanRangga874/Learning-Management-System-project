<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserProgressController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AdminFaqController;

// ====================================================
// RUTE PUBLIK (Bisa diakses siapa saja)
// ====================================================
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/course/{course:slug}', [FrontController::class, 'details'])->name('front.details');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

// ====================================================
// RUTE SISWA / AUTH (Butuh Login)
// ====================================================
Route::middleware(['auth'])->group(function () {
    // Dashboard Siswa
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified')
        ->name('dashboard');

    // Proses Belajar
    Route::post('/course/{course:slug}/join', [FrontController::class, 'join'])->name('front.join');
    Route::get('/learning/{course:slug}/{lesson?}', [FrontController::class, 'learning'])->name('front.learning');
    
    // Kuis & Penyelesaian Materi
    Route::get('/course/{course:slug}/lesson/{lesson}/quiz', [FrontController::class, 'startQuiz'])->name('front.quiz');
    Route::get('/course/{course:slug}/lesson/{lesson}/results', [FrontController::class, 'quizResults'])->name('front.quiz.results');
    Route::post('/course/{course:slug}/lesson/{lesson}/submit', [FrontController::class, 'submitQuiz'])->name('front.quiz.submit');
    Route::post('/course/{course:slug}/lesson/{lesson}/complete', [FrontController::class, 'markAsComplete'])->name('front.lesson.complete');
    
    // Sertifikat
    Route::post('/course/{course}/certificate', [CertificateController::class, 'request'])->name('front.certificate.request');
    Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])->name('front.certificate.download');

    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================================================
// RUTE ADMIN (Manajemen Konten & Penilaian)
// ====================================================
// [PENTING] Middleware 'is_admin' ditambahkan di sini
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Manajemen Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('courses.chapters', ChapterController::class);
    Route::resource('chapters.lessons', LessonController::class);
    Route::resource('faqs', AdminFaqController::class);
    Route::post('/faqs/contact', [AdminFaqController::class, 'updateContact'])->name('faqs.update_contact');
    Route::get('courses/{course}/assignments', [CourseController::class, 'assignments'])->name('courses.assignments');
    
    // Manajemen Soal
    Route::get('lessons/{lesson}/questions', [QuestionController::class, 'index'])->name('lessons.questions.index');
    Route::post('lessons/{lesson}/questions', [QuestionController::class, 'store'])->name('lessons.questions.store');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // --- GRADING / PENILAIAN ---
    Route::get('lessons/{lesson}/users', [UserProgressController::class, 'index'])->name('lessons.users.index');
    Route::get('lessons/{lesson}/users/{user}', [UserProgressController::class, 'show'])->name('lessons.users.show');
    Route::put('answers/{userAnswer}/score', [UserProgressController::class, 'updateScore'])->name('answers.updateScore');
    Route::put('lessons/{lesson}/users/{user}/grade', [UserProgressController::class, 'updateAllScores'])->name('lessons.users.gradeAll');

    // --- MANAJEMEN SERTIFIKAT & USER ---
    
    // [FIX] Route Settings HARUS Ditaruh DI ATAS Route {certificate} (Wildcard)
    Route::get('/certificates/settings', [AdminCertificateController::class, 'settings'])->name('certificates.settings');
    Route::put('/certificates/settings', [AdminCertificateController::class, 'updateSettings'])->name('certificates.update_settings');

    // Route Resource & Manual lainnya di bawahnya
    Route::get('/certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::put('/certificates/{certificate}', [AdminCertificateController::class, 'update'])->name('certificates.update');
    
    Route::resource('certificates', AdminCertificateController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';