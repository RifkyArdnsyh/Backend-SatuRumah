<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\DokumenController;

/*
|--------------------------------------------------------------------------
| SATU RUMAH REST API Routes - Version 1
| Dinas Perumahan dan Kawasan Permukiman Kota Tasikmalaya
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // 1. Public Authentication Route
    Route::post('/auth/login', [AuthController::class, 'login']);

    // 2. Protected Routes via Laravel Sanctum
    Route::middleware('auth:sanctum')->group(function () {

        // Session & Identity
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // CRUD Pengajuan (Mencegah IDOR via Scope & PengajuanPolicy)
        Route::get('/pengajuan', [PengajuanController::class, 'index']);
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->middleware('role:pengembang,developer');
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show']);
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update']);

        // Upload Dokumen & Site Plan (Terproteksi FormRequest & Magic Bytes)
        Route::post('/pengajuan/{id}/dokumen', [DokumenController::class, 'upload'])->middleware('role:pengembang,developer');

        // Download Dokumen Sensitif Privat (Terproteksi PengajuanPolicy)
        Route::get('/dokumen/{id}/download', [DokumenController::class, 'download']);

        // Verifikasi Dokumen & Tahapan Teknis (Hanya Verifikator & Administrator)
        Route::post('/pengajuan/{id}/verifikasi-berkas', [PengajuanController::class, 'verifikasiBerkas'])
            ->middleware('role:verifikator,admin_disperwaskim');

        // Persetujuan SK Akhir (Hanya Pimpinan / Kepala Dinas)
        Route::post('/pengajuan/{id}/approve-sk', [PengajuanController::class, 'approveSk'])
            ->middleware('role:pimpinan,admin_disperwaskim');
    });
});
