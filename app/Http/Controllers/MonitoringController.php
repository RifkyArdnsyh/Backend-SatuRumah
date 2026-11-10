<?php

namespace App\Http\Controllers;

use App\Models\MonitoringPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MonitoringController extends Controller
{
    public function toggleStatus(Request $request, $pengajuan_id)
    {
        $pengajuan = Pengajuan::findOrFail($pengajuan_id);
        Gate::authorize('manageMonitoring', $pengajuan);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $pengajuan_id) {
            $monitoring = MonitoringPengajuan::where('pengajuan_id', $pengajuan_id)->lockForUpdate()->first();
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
        });
    }

    public function submitAction(Request $request, $pengajuan_id)
    {
        Gate::authorize('manageMonitoring', $pengajuan);

        $action = $request->input('action'); // 'perbaikan' or 'persetujuan'

        return \Illuminate\Support\Facades\DB::transaction(function () use ($action, $pengajuan_id) {
            $pengajuan = Pengajuan::lockForUpdate()->findOrFail($pengajuan_id);

            if ($action === 'persetujuan') {
                $pengajuan->transitionTo('Persetujuan', 'Siap disetujui');

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
                $pengajuan->transitionTo('Verifikasi teknis', 'Perlu perbaikan');

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
        });
    }
}
