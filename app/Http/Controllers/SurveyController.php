<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\SurveyPengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request, $pengajuan_id)
    {
        $request->validate([
            'tanggal_survey' => 'required|string',
            'jam_survey' => 'nullable|string',
            'petugas_nama' => 'required|string',
            'instruksi' => 'nullable|string',
        ]);

        $jamSurvey = $request->filled('jam_survey') ? $request->jam_survey : '09.00 WIB';

        // Mark previous active survey as Dijadwalkan Ulang if existing
        SurveyPengajuan::where('pengajuan_id', $pengajuan_id)
            ->where('status', 'Terjadwal')
            ->update(['status' => 'Dijadwalkan Ulang']);

        $survey = SurveyPengajuan::create([
            'pengajuan_id' => $pengajuan_id,
            'tanggal_survey' => $request->tanggal_survey,
            'jam_survey' => $jamSurvey,
            'petugas_nama' => $request->petugas_nama,
            'petugas_role' => 'Perwaskim',
            'instruksi' => $request->instruksi,
            'status' => 'Terjadwal',
        ]);

        // Update pengajuan status & tahap
        $pengajuan = Pengajuan::findOrFail($pengajuan_id);
        $pengajuan->tahap = 'Survey';
        $pengajuan->status = 'Terjadwal';
        $pengajuan->save();

        RiwayatPengajuan::create([
            'pengajuan_id' => $pengajuan_id,
            'judul' => 'Penjadwalan Survey Dikonfirmasi',
            'deskripsi' => "Survey dijadwalkan pada {$request->tanggal_survey} pukul {$jamSurvey} dengan petugas {$request->petugas_nama}.",
            'oleh' => auth()->user()->name ?? 'Verifikator Disperwaskim',
            'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
        ]);

        return redirect()->route('pengajuan.show', ['id' => $pengajuan_id, 'tab' => 'monitoring'])
            ->with('success', 'Jadwal survey berhasil disimpan!');
    }
}
