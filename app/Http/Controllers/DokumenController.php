<?php

namespace App\Http\Controllers;

use App\Models\DokumenPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use App\Services\SuresmanTteService;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Sesuai,Perlu perbaikan,Belum diperiksa',
            'catatan' => 'nullable|string',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
            $dokumen = DokumenPengajuan::lockForUpdate()->findOrFail($id);

            $dokumen->status = $request->status;
            if ($request->has('catatan')) {
                $dokumen->catatan = $request->catatan;
            }
            $dokumen->save();

            // Auto-recalculate submission status
            $pengajuan = Pengajuan::with('dokumen')->lockForUpdate()->findOrFail($dokumen->pengajuan_id);
            $perluPerbaikanCount = $pengajuan->dokumen->where('status', 'Perlu perbaikan')->count();

            if ($perluPerbaikanCount > 0) {
                $pengajuan->transitionTo(
                    $pengajuan->tahap,
                    'Perlu perbaikan',
                    "Terdapat {$perluPerbaikanCount} dokumen yang memerlukan perbaikan sebelum proses dapat dilanjutkan ke penjadwalan survey."
                );
            }

            RiwayatPengajuan::create([
                'pengajuan_id' => $dokumen->pengajuan_id,
                'judul' => 'Verifikasi Dokumen: ' . $dokumen->nama_persyaratan,
                'deskripsi' => "Status dokumen diubah menjadi '{$dokumen->status}'" . ($request->catatan ? ". Catatan: {$request->catatan}" : ""),
                'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return back()->with('success', 'Status dokumen ' . $dokumen->nama_persyaratan . ' berhasil diperbarui!');
        });
    }

    /**
     * Preview langsung gambar visualisasi TTE dari Suresman (bisa dipanggil di frontend)
     */
    public function previewTte(Request $request, SuresmanTteService $tteService)
    {
        $nip = $request->query('nip');
        if (!$nip) {
            return response()->json(['error' => 'NIP diperlukan'], 422);
        }

        $imagePath = $tteService->getOrDownloadTte($nip, $request->query('nama'), $request->query('jabatan'), true);

        if (!$imagePath || !file_exists($imagePath)) {
            return response()->json(['error' => 'Gagal mengambil TTE dari Suresman'], 404);
        }

        return response()->file($imagePath, [
            'Content-Type' => 'image/png',
        ]);
    }

    /**
     * Cetak dokumen PDF resmi dengan tanda tangan TTE Suresman
     */
    public function cetakPdf($id, SuresmanTteService $tteService)
    {
        // 1. Ambil data dokumen atau pengajuan
        $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);

        // 2. Tentukan data penandatangan (Pejabat Disperwaskim Kota Tasikmalaya)
        $nipPejabat = config('app.pejabat_nip', '197109092006041008'); // H. Sandi Lesmana, ST., M.Si.
        $namaPejabat = config('app.pejabat_nama', 'H. Sandi Lesmana, ST., M.Si.');
        $jabatanPejabat = config('app.pejabat_jabatan', 'Kepala Dinas Perumahan Rakyat dan Kawasan Permukiman');

        // 3. Ambil absolute path gambar TTE
        $tteAbsolutePath = $tteService->getOrDownloadTte($nipPejabat, $namaPejabat, $jabatanPejabat, true);

        // 4. Render ke PDF (Contoh jika memakai DomPDF atau view generator)
        // $pdf = Pdf::loadView('pdf.surat_rekomendasi', compact('dokumen', 'tteAbsolutePath'));
        // return $pdf->download("dokumen_{$dokumen->id}.pdf");

        return response()->json([
            'status' => 'success',
            'dokumen_id' => $dokumen->id,
            'tte_path' => $tteAbsolutePath,
            'message' => 'Path TTE berhasil disiapkan untuk kompilasi PDF'
        ]);
    }

    /**
     * Unduh file dokumen privat dengan pengecekan Gate PengajuanPolicy
     */
    public function unduh($id)
    {
        $dokumen = DokumenPengajuan::with('pengajuan')->findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('view', $dokumen->pengajuan);

        $filePath = $dokumen->nama_file;
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($filePath)) {
            abort(404, 'Berkas fisik tidak ditemukan di penyimpanan server.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download($filePath, basename($filePath));
    }
}
