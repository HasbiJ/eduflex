<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

    // ---- Area CRUD anggota tim (tambahkan di sini) ----
    // Anggota 1: Route::resource('subjects', SubjectController::class);
    // Anggota 2: Route::resource('learning-videos', LearningVideoController::class);
    // Anggota 3: Route::resource('attendance', AttendanceController::class);
    // Route::get('/schedule', [ScheduleController::class, 'index']);
});
