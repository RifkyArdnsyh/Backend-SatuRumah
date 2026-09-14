<?php

namespace App\Http\Controllers;

use App\Models\MonitoringPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function toggleStatus(Request $request, $pengajuan_id)
    {
        $monitoring = MonitoringPengajuan::where('pengajuan_id', $pengajuan_id)->first();
        if (!$monitoring) {
            $monitoring = MonitoringPengajuan::create([
                'pengajuan_id' => $pengajuan_id,
                'status_monitoring' => 'Draft',
                'tanggal_survey' => '22 Mei 2025',
                'petugas_nama' => 'Rahmat Hidayat, S.T.',
                'hasil' => 'Perlu Evaluasi',
            ]);
        }

        $newStatus = ($monitoring->status_monitoring === 'Draft') ? 'Final' : 'Draft';
        $monitoring->status_monitoring = $newStatus;
        if ($newStatus === 'Final') {
            $monitoring->hasil = 'Disetujui';
        } else {
            $monitoring->hasil = 'Perlu Evaluasi';
        }
        $monitoring->save();

        RiwayatPengajuan::create([
            'pengajuan_id' => $pengajuan_id,
            'judul' => 'Status Monitoring Diperbarui',
            'deskripsi' => "Status hasil monitoring diubah menjadi '{$newStatus}'.",
            'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
            'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
        ]);

        return redirect()->route('pengajuan.show', ['id' => $pengajuan_id, 'tab' => 'monitoring'])
            ->with('success', "Status monitoring berhasil diubah ke {$newStatus}!");
    }

    public function submitAction(Request $request, $pengajuan_id)
    {
        $action = $request->input('action'); // 'perbaikan' or 'persetujuan'
        $pengajuan = Pengajuan::findOrFail($pengajuan_id);

        if ($action === 'persetujuan') {
            $pengajuan->tahap = 'Persetujuan';
            $pengajuan->status = 'Siap disetujui';
            $pengajuan->save();

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan_id,
                'judul' => 'Lanjutkan ke Persetujuan',
                'deskripsi' => 'Pengajuan berhasil dilanjutkan ke tahap Persetujuan akhir.',
                'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return redirect()->route('pengajuan.show', ['id' => $pengajuan_id, 'tab' => 'ringkasan'])
                ->with('success', 'Pengajuan berhasil dilanjutkan ke tahap Persetujuan!');
        } else {
            $pengajuan->tahap = 'Verifikasi teknis';
            $pengajuan->status = 'Perlu perbaikan';
            $pengajuan->save();

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan_id,
                'judul' => 'Dikembalikan ke Perbaikan',
                'deskripsi' => 'Pengajuan dikembalikan ke pengembang untuk perbaikan teknis.',
                'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return redirect()->route('pengajuan.show', ['id' => $pengajuan_id, 'tab' => 'monitoring'])
                ->with('warning', 'Pengajuan dikembalikan ke tahap perbaikan.');
        }
    }
}
