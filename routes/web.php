<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NotifikasiController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'exportCsv'])->name('dashboard.export');

    // Pengajuan Routes
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::post('/pengajuan/{id}/auto-verifikasi', [PengajuanController::class, 'autoVerifikasi'])->name('pengajuan.auto_verifikasi');
    Route::post('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.update_status');

    // Dokumen Routes
    Route::post('/dokumen/{id}/status', [DokumenController::class, 'updateStatus'])->name('dokumen.update_status');

    // Survey Routes
    Route::post('/pengajuan/{id}/survey', [SurveyController::class, 'store'])->name('survey.store');

    // Monitoring Routes
    Route::post('/pengajuan/{id}/monitoring/toggle', [MonitoringController::class, 'toggleStatus'])->name('monitoring.toggle');
    Route::post('/pengajuan/{id}/monitoring/action', [MonitoringController::class, 'submitAction'])->name('monitoring.action');

    // Notifikasi Routes
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read_all');
});
