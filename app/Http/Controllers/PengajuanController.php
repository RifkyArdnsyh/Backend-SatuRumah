<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\DokumenPengajuan;
use App\Models\Notifikasi;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nama_pengembang', 'like', "%{$search}%")
                  ->orWhere('nama_perumahan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tahap') && $request->tahap !== 'semua') {
            $query->where('tahap', $request->tahap);
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $totalSemua   = Pengajuan::count();
        $totalAktif   = Pengajuan::whereNotIn('status', ['Selesai', 'Ditolak'])->count();
        $totalMenunggu = Pengajuan::where('status', 'Menunggu verifikasi')->count();
        $totalPerlu    = Pengajuan::where('status', 'Perlu perbaikan')->count();

        $unreadCount = \App\Models\Notifikasi::where('is_read', false)->count();

        return view('admin.pengajuan.index', compact(
            'pengajuans',
            'totalSemua',
            'totalAktif',
            'totalMenunggu',
            'totalPerlu',
            'unreadCount',
        ));
    }

    public function show(Request $request, $id)
    {
        $pengajuan = Pengajuan::with([
            'dokumen',
            'survey',
            'activeSurvey',
            'monitoring',
            'riwayat',
        ])->findOrFail($id);

        $activeTab = $request->query('tab', 'ringkasan');
        
        // If legacy 'survey' tab is requested, map it to 'monitoring'
        if ($activeTab === 'survey' || $activeTab === 'verifikasi') {
            $activeTab = ($activeTab === 'verifikasi') ? 'dokumen' : 'monitoring';
        }

        $unreadCount = Notifikasi::where('is_read', false)->count();

        // Group documents by kategori
        $dokumenPerusahaan = $pengajuan->dokumen->where('kategori', 'perusahaan');
        $dokumenPerumahan = $pengajuan->dokumen->where('kategori', 'perumahan');
        $paketTeknis = $pengajuan->dokumen->where('kategori', 'paket_teknis')->first();

        return view('admin.pengajuan.show', compact(
            'pengajuan',
            'activeTab',
            'unreadCount',
            'dokumenPerusahaan',
            'dokumenPerumahan',
            'paketTeknis'
        ));
    }

    public function autoVerifikasi($id)
    {
        $pengajuan = Pengajuan::with('dokumen')->findOrFail($id);
        
        $perluPerbaikanCount = $pengajuan->dokumen->where('status', 'Perlu perbaikan')->count();
        $belumDiperiksaCount = $pengajuan->dokumen->where('status', 'Belum diperiksa')->count();

        if ($perluPerbaikanCount > 0) {
            $pengajuan->tahap = 'Verifikasi teknis';
            $pengajuan->status = 'Perlu perbaikan';
            $pengajuan->catatan_status = "Verifikasi Berkas: Ditemukan {$perluPerbaikanCount} berkas yang memerlukan perbaikan sebelum proses dapat dilanjutkan ke penjadwalan survey.";
            $pengajuan->save();

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Verifikasi Berkas Selesai',
                'deskripsi' => "Hasil verifikasi mendeteksi {$perluPerbaikanCount} dokumen perlu perbaikan. Pengembang diminta melengkapi berkas terkait.",
                'oleh' => 'Verifikator Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return redirect()->route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'dokumen'])
                ->with('warning', "Pemeriksaan selesai: Terdapat {$perluPerbaikanCount} dokumen yang perlu diperbaiki.");
        }

        // If there are uninspected documents without errors, auto-verify all as Sesuai
        if ($belumDiperiksaCount > 0) {
            DokumenPengajuan::where('pengajuan_id', $pengajuan->id)
                ->where('status', 'Belum diperiksa')
                ->update(['status' => 'Sesuai']);
        }

        $pengajuan->tahap = 'Survey';
        $pengajuan->status = 'Terjadwal';
        $pengajuan->catatan_status = 'Verifikasi Berkas Selesai: Seluruh dokumen persyaratan wajib telah diverifikasi dan dinyatakan SESUAI. Pengajuan siap untuk survey & monitoring.';
        $pengajuan->save();

        RiwayatPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'judul' => 'Verifikasi Berkas Disetujui',
            'deskripsi' => 'Seluruh dokumen persyaratan administratif dan teknis telah diperiksa dan dinyatakan SESUAI.',
            'oleh' => 'Verifikator Disperwaskim',
            'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
        ]);

        return redirect()->route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'monitoring'])
            ->with('success', 'Verifikasi berkas berhasil! Seluruh dokumen sesuai dan pengajuan siap masuk tahap Survey & Monitoring.');
    }

    public function updateStatus(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
            'tahap' => 'nullable|string',
        ]);

        $statusLama = $pengajuan->status;
        $pengajuan->status = $request->status;
        if ($request->filled('tahap')) {
            $pengajuan->tahap = $request->tahap;
        }
        $pengajuan->save();

        RiwayatPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'judul' => 'Status Pengajuan Diperbarui',
            'deskripsi' => "Status berubah dari '{$statusLama}' menjadi '{$pengajuan->status}'.",
            'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
            'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
