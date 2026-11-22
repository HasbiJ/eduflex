<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LearningVideoController;

// Halaman awal diarahkan ke login
Route::get('/', fn () => redirect()->route('login'));

// ===== Route untuk tamu (belum login) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ===== Route khusus GURU (harus login + role guru) =====
Route::middleware(['auth', 'guru'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('learning-videos', LearningVideoController::class);

    // ==================================================
    // AREA KERJA ANGGOTA 1 : SUBJECTS
    // ==================================================

    // CRUD kelas
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{id}', [SubjectController::class, 'show'])->name('subjects.show'); // ?tab=stream|classwork|people
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::post('/subjects/{id}/reset-code', [SubjectController::class, 'resetCode'])->name('subjects.resetCode');

    // Stream & Classwork (pengumuman, task, quiz, module)
    Route::post('/subjects/{id}/posts', [SubjectController::class, 'storePost'])->name('posts.store');
    Route::put('/subjects/{id}/posts/{postId}', [SubjectController::class, 'updatePost'])->name('posts.update');
    Route::delete('/subjects/{id}/posts/{postId}', [SubjectController::class, 'destroyPost'])->name('posts.destroy');

    // Komentar pada postingan
    Route::post('/subjects/{id}/posts/{postId}/comments', [SubjectController::class, 'storeComment'])->name('comments.store');

    // ==================================================
    // AREA KERJA : PROFIL GURU
    // ==================================================
    Route::get('/profile', [TeacherProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [TeacherProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile/{id}', [TeacherProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile-password', [TeacherProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/{id}', [TeacherProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================================================
    // Anggota 2: Route::resource('learning-videos', LearningVideoController::class);
    // Anggota 3: Route::resource('attendance', AttendanceController::class);
    // Route::get('/schedule', [ScheduleController::class, 'index']);
});