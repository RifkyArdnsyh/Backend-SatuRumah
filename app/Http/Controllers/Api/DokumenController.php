<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadDokumenRequest;
use App\Models\DokumenPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    /**
     * Upload dokumen persyaratan atau berkas teknis/DWG ke storage privat.
     */
    public function upload(UploadDokumenRequest $request, $pengajuan_id)
    {
        $pengajuan = Pengajuan::findOrFail($pengajuan_id);
        Gate::authorize('update', $pengajuan);

        $file = $request->file('berkas');
        $ext = strtolower($file->getClientOriginalExtension());
        $safeName = (string) Str::uuid() . '.' . $ext;

        return DB::transaction(function () use ($request, $pengajuan, $file, $safeName) {
            // Simpan berkas di disk 'local' (privat)
            $storedPath = $file->storeAs("dokumen/{$pengajuan->id}", $safeName, 'local');

            $sizeInKb = round($file->getSize() / 1024, 2);
            $sizeStr = $sizeInKb >= 1024 
                ? round($sizeInKb / 1024, 2) . ' MB' 
                : $sizeInKb . ' KB';

            $dokumen = DokumenPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'kategori' => $request->kategori,
                'nama_persyaratan' => $request->nama_persyaratan,
                'nama_file' => $storedPath,
                'ukuran_file' => $sizeStr,
                'tanggal_unggah' => now()->translatedFormat('d M Y, H:i'),
                'status' => 'Belum diperiksa',
            ]);

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Dokumen Diunggah',
                'deskripsi' => "Pengembang mengunggah berkas: {$request->nama_persyaratan}.",
                'oleh' => $request->user()->name,
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berkas berhasil diunggah ke storage privat.',
                'data' => $dokumen,
                'errors' => null,
            ], 201);
        });
    }

    /**
     * Download/Stream dokumen privat dengan pengecekan otorisasi PengajuanPolicy.
     */
    public function download($id)
    {
        $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);

        // Otorisasi: hanya pemilik atau verifikator yang boleh mengakses berkas ini
        Gate::authorize('view', $dokumen->pengajuan);

        $filePath = $dokumen->nama_file;

        if (!Storage::disk('local')->exists($filePath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berkas fisik tidak ditemukan di server.',
                'data' => null,
                'errors' => null,
            ], 404);
        }

        return Storage::disk('local')->download($filePath, basename($filePath));
    }
}
