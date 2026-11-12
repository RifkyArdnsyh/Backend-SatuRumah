<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PengajuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan.
     * Mencegah IDOR: Pengembang otomatis hanya melihat pengajuan miliknya sendiri.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Pengajuan::with(['dokumen', 'activeSurvey', 'monitoring']);

        // Scope data: Developer / Pengembang hanya dapat melihat datanya sendiri
        $role = strtolower($user->role ?? '');
        if (in_array($role, ['pengembang', 'developer'], true)) {
            $query->where('user_id', $user->id);
        }

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

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 10));

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar pengajuan berhasil diambil.',
            'data' => $pengajuans,
            'errors' => null,
        ], 200);
    }

    /**
     * Simpan pengajuan baru (khusus Developer / Pengembang).
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Pengajuan::class);

        $validated = $request->validate([
            'nama_pengembang' => 'required|string|max:255',
            'nama_perumahan' => 'required|string|max:255',
            'lokasi' => 'required|string',
            'jumlah_unit' => 'required|string',
            'luas_kawasan' => 'required|string',
            'penanggung_jawab' => 'required|string',
        ]);

        $user = $request->user();

        // Generate ID standar: SR-YYYY-XXXX
        $year = now()->format('Y');
        $random = rand(1000, 9999);
        $submissionId = "SR-{$year}-{$random}";

        return DB::transaction(function () use ($user, $validated, $submissionId) {
            $pengajuan = Pengajuan::create([
                'id' => $submissionId,
                'user_id' => $user->id,
                'nama_pengembang' => $validated['nama_pengembang'],
                'nama_perumahan' => $validated['nama_perumahan'],
                'lokasi' => $validated['lokasi'],
                'jumlah_unit' => $validated['jumlah_unit'],
                'luas_kawasan' => $validated['luas_kawasan'],
                'penanggung_jawab' => $validated['penanggung_jawab'],
                'tahap' => 'Dokumen',
                'status' => 'Menunggu verifikasi',
                'catatan_status' => 'Pengajuan baru telah dikirim dan menunggu verifikasi berkas oleh Disperwaskim.',
                'diajukan_pada' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Pengajuan Dibuat',
                'deskripsi' => "Pengajuan berhasil didaftarkan oleh {$user->name}.",
                'oleh' => $user->name,
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan berhasil dibuat.',
                'data' => $pengajuan->load('riwayat'),
                'errors' => null,
            ], 201);
        });
    }

    /**
     * Menampilkan detail pengajuan dengan proteksi Policy IDOR.
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'user:id,name,email,role',
            'dokumen',
            'survey',
            'activeSurvey',
            'monitoring',
            'riwayat',
        ])->findOrFail($id);

        Gate::authorize('view', $pengajuan);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail pengajuan berhasil diambil.',
            'data' => $pengajuan,
            'errors' => null,
        ], 200);
    }

    /**
     * Perbarui pengajuan (khusus pengembang atau verifikator dengan hak akses).
     */
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        Gate::authorize('update', $pengajuan);

        $validated = $request->validate([
            'lokasi' => 'nullable|string',
            'jumlah_unit' => 'nullable|string',
            'luas_kawasan' => 'nullable|string',
            'penanggung_jawab' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($pengajuan, $validated, $request) {
            $pengajuan->update(array_filter($validated));

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Perubahan Data Permohonan',
                'deskripsi' => 'Pengembang telah memperbarui rincian permohonan.',
                'oleh' => $request->user()->name,
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengajuan berhasil diperbarui.',
                'data' => $pengajuan,
                'errors' => null,
            ], 200);
        });
    }

    /**
     * Verifikasi berkas permohonan (Terkunci hanya untuk Verifikator / Admin Disperwaskim).
     */
    public function verifikasiBerkas(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $pengajuan = Pengajuan::with('dokumen')->lockForUpdate()->findOrFail($id);

            $perluPerbaikanCount = $pengajuan->dokumen->where('status', 'Perlu perbaikan')->count();

            if ($perluPerbaikanCount > 0) {
                $pengajuan->tahap = 'Verifikasi teknis';
                $pengajuan->status = 'Perlu perbaikan';
                $pengajuan->catatan_status = "Verifikasi Berkas: Ditemukan {$perluPerbaikanCount} berkas yang memerlukan perbaikan sebelum penjadwalan survey.";
                $pengajuan->save();

                RiwayatPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'judul' => 'Verifikasi Berkas: Perlu Perbaikan',
                    'deskripsi' => "Hasil verifikasi mendeteksi {$perluPerbaikanCount} dokumen perlu perbaikan.",
                    'oleh' => $request->user()->name ?? 'Verifikator Disperwaskim',
                    'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => "Pemeriksaan selesai: {$perluPerbaikanCount} dokumen perlu perbaikan.",
                    'data' => $pengajuan,
                    'errors' => null,
                ], 200);
            }

            // Tandai semua dokumen menjadi Sesuai
            \App\Models\DokumenPengajuan::where('pengajuan_id', $pengajuan->id)
                ->where('status', 'Belum diperiksa')
                ->update(['status' => 'Sesuai']);

            $pengajuan->tahap = 'Survey';
            $pengajuan->status = 'Terjadwal';
            $pengajuan->catatan_status = 'Verifikasi Berkas Selesai: Seluruh dokumen persyaratan wajib dinyatakan SESUAI. Pengajuan siap survey & monitoring.';
            $pengajuan->save();

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Verifikasi Berkas Disetujui',
                'deskripsi' => 'Seluruh dokumen persyaratan administratif dan teknis telah diperiksa dan dinyatakan SESUAI.',
                'oleh' => $request->user()->name ?? 'Verifikator Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Verifikasi berkas berhasil disetujui. Pengajuan lanjut ke tahap Survey.',
                'data' => $pengajuan,
                'errors' => null,
            ], 200);
        });
    }

    /**
     * Persetujuan SK Akhir Site Plan (Terkunci hanya untuk Pimpinan / Kepala Dinas).
     */
    public function approveSk(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $pengajuan = Pengajuan::lockForUpdate()->findOrFail($id);

            $pengajuan->tahap = 'Persetujuan';
            $pengajuan->status = 'Selesai';
            $pengajuan->catatan_status = 'Persetujuan SK Site Plan telah disahkan dan ditandatangani secara elektronik (TTE).';
            $pengajuan->save();

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'judul' => 'Persetujuan SK Site Plan Disahkan',
                'deskripsi' => 'Surat Keputusan (SK) Persetujuan Site Plan telah ditandatangani oleh Kepala Dinas Disperwaskim.',
                'oleh' => $request->user()->name ?? 'Pimpinan Disperwaskim',
                'tanggal' => now()->translatedFormat('d F Y · H.i') . ' WIB',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'SK Persetujuan Site Plan berhasil disahkan.',
                'data' => $pengajuan,
                'errors' => null,
            ], 200);
        });
    }
}
