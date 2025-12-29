<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BossDashboardController;
use App\Http\Controllers\AdminDashboardController; // Pastikan controller ini ada
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\IncomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. ROUTE PUBLIC (LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =============================================================
//  AREA OPERASIONAL BERSAMA (BOSS & ADMIN)
//  (Admin bisa akses ini untuk kelola jadwal, tapi Boss juga bisa)
// =============================================================
Route::middleware(['auth', 'role:boss,admin'])->group(function () {

    // CRUD Job
    Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // Fitur Tambahan Job
    Route::post('/jobs/{job}/cancel', [JobController::class, 'cancel'])->name('jobs.cancel');
    Route::put('/jobs/{job}/update-link', [JobController::class, 'updateLink'])->name('jobs.updateLink');
    Route::post('/jobs/{job}/update-proof', [JobController::class, 'updateProof'])->name('jobs.updateProof');

    // API Helper
    Route::get('/api/check-availability', [JobController::class, 'checkAvailability'])->name('api.checkAvailability');

    // Kelola Job Types
    Route::resource('job-types', JobTypeController::class)->only(['index', 'store', 'destroy']);

    Route::get('/jobs/{job}/send-wa', [JobController::class, 'sendWhatsapp'])->name('jobs.sendWa');

    Route::post('/jobs/{job}/confirm-payment', [JobController::class, 'confirmPayment'])->name('jobs.confirmPayment');

    Route::get('/jobs/{job}/mark-wa-sent', [JobController::class, 'markWaSent'])->name('jobs.markWaSent');
});


// =============================================================
//  AREA KHUSUS BOSS (KEUANGAN & SUPER ADMIN)
// =============================================================
Route::middleware(['auth', 'role:boss'])->group(function () {
    Route::get('/boss/dashboard', [BossDashboardController::class, 'index'])->name('boss.dashboard');

    // Kelola User & Gaji (Hanya Boss)
    Route::resource('users', UserController::class);

    // Laporan Income
    Route::get('/boss/income', [IncomeController::class, 'index'])->name('boss.income.index');
    Route::post('/boss/income', [IncomeController::class, 'update'])->name('boss.income.update');
    Route::get('/boss/income/{user}', [IncomeController::class, 'detail'])->name('boss.income.detail');
    Route::post('/boss/income/store-single', [IncomeController::class, 'storeSingleIncome'])->name('boss.income.storeSingle');
    Route::post('/boss/income/cairkan', [IncomeController::class, 'cairkanGaji'])->name('boss.income.cairkan');
});


// =============================================================
//  AREA KHUSUS ADMIN (DASHBOARD)
// =============================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});


// =============================================================
//  AREA CREW
// =============================================================
Route::middleware(['auth', 'role:crew'])->group(function () {
    Route::get('/my-jobs', [CrewController::class, 'index'])->name('crew.jobs');
    Route::get('/my-jobs/{job}', [CrewController::class, 'show'])->name('crew.show');

    Route::post('/jobs/{job}/start', [CrewController::class, 'startJob'])->name('crew.start');
    Route::post('/jobs/{job}/finish', [CrewController::class, 'finishJob'])->name('crew.finish');

    // ROUTE BARU: Update Progress (OTW, Arrived, Start)
    Route::post('/jobs/{job}/progress/{status}', [CrewController::class, 'updateProgress'])->name('crew.progress');
});


// =============================================================
//  AREA EDITOR
// =============================================================
Route::middleware(['auth', 'role:editor'])->group(function () {
    Route::get('/editor/dashboard', [EditorController::class, 'index'])->name('editor.dashboard');
    Route::get('/editor/job/{id}', [EditorController::class, 'show'])->name('editor.show');

    Route::post('/editor/{job}/start', [EditorController::class, 'start'])->name('editor.start');
    Route::post('/editor/{job}/finish', [EditorController::class, 'finishJob'])->name('editor.finish');
});
