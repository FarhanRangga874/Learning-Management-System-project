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

// Rute Publik
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/course/{course:slug}', [FrontController::class, 'details'])->name('front.details');

// Rute Butuh Login (Siswa)
Route::middleware(['auth'])->group(function () {
    Route::post('/course/{course:slug}/join', [FrontController::class, 'join'])->name('front.join');
    Route::get('/learning/{course:slug}/{lesson?}', [FrontController::class, 'learning'])->name('front.learning');
    Route::get('/course/{course:slug}/lesson/{lesson}/quiz', [FrontController::class, 'startQuiz'])->name('front.quiz');
    Route::post('/course/{course:slug}/lesson/{lesson}/submit', [FrontController::class, 'submitQuiz'])->name('front.quiz.submit');
    Route::post('/course/{course:slug}/lesson/{lesson}/complete', [FrontController::class, 'markAsComplete'])->name('front.lesson.complete');
    Route::post('/course/{course}/certificate', [CertificateController::class, 'request'])->name('front.certificate.request');
    Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])->name('front.certificate.download');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('courses.chapters', ChapterController::class);
    Route::resource('chapters.lessons', LessonController::class);
    Route::get('lessons/{lesson}/questions', [QuestionController::class, 'index'])->name('lessons.questions.index');
    Route::post('lessons/{lesson}/questions', [QuestionController::class, 'store'])->name('lessons.questions.store');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('lessons/{lesson}/users', [UserProgressController::class, 'index'])->name('lessons.users.index');
    Route::get('lessons/{lesson}/users/{user}', [UserProgressController::class, 'show'])->name('lessons.users.show');
    Route::put('answers/{userAnswer}/score', [UserProgressController::class, 'updateScore'])->name('answers.updateScore');
    Route::get('/certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::put('/certificates/{certificate}', [AdminCertificateController::class, 'update'])->name('certificates.update');
    Route::resource('faqs', AdminFaqController::class);
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ... route resource lain ...
    
    // Ubah dari 'students' menjadi 'users'
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';
