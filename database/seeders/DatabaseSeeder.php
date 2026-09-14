<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pengajuan;
use App\Models\DokumenPengajuan;
use App\Models\SurveyPengajuan;
use App\Models\MonitoringPengajuan;
use App\Models\RiwayatPengajuan;
use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Verifikator Disperwaskim & Surveyor Users
        User::updateOrCreate(
            ['email' => 'admin@saturumah.go.id'],
            [
                'name' => 'Verifikator Disperwaskim',
                'password' => Hash::make('password'),
                'role' => 'verifikator',
                'avatar_initials' => 'VD',
            ]
        );

        User::updateOrCreate(
            ['email' => 'verifikator@saturumah.go.id'],
            [
                'name' => 'Verifikator Disperwaskim',
                'password' => Hash::make('password'),
                'role' => 'verifikator',
                'avatar_initials' => 'VD',
            ]
        );

        User::updateOrCreate(
            ['email' => 'rahmat@saturumah.go.id'],
            [
                'name' => 'Rahmat Hidayat, S.T.',
                'password' => Hash::make('password'),
                'role' => 'perwaskim',
                'avatar_initials' => 'RH',
            ]
        );

        // 2. Create Submissions (Pengajuan)
        $p1 = Pengajuan::create([
            'id' => 'SR-2025-0148',
            'nama_pengembang' => 'PT Citra Tasik Mandiri',
            'nama_perumahan' => 'Griya Mangkubumi Asri',
            'lokasi' => 'Kec. Mangkubumi, Kota Tasikmalaya',
            'jumlah_unit' => '148 unit rumah tapak',
            'luas_kawasan' => '34.800 m²',
            'penanggung_jawab' => 'H. Asep Hendrayana, S.T.',
            'tahap' => 'Verifikasi teknis',
            'status' => 'Perlu perbaikan',
            'catatan_status' => 'Terdapat 3 dokumen yang memerlukan perbaikan sebelum proses dapat dilanjutkan ke penjadwalan survey.',
            'diajukan_pada' => '08 Mei 2025 · 09.21 WIB',
        ]);

        $p2 = Pengajuan::create([
            'id' => 'SR-2025-0147',
            'nama_pengembang' => 'PT Sukapura Mandiri Properti',
            'nama_perumahan' => 'Kencana Kawalu Regency',
            'lokasi' => 'Kec. Kawalu, Kota Tasikmalaya',
            'jumlah_unit' => '85 unit rumah tapak',
            'luas_kawasan' => '18.500 m²',
            'penanggung_jawab' => 'Dedi Setiadi, S.T.',
            'tahap' => 'Survey',
            'status' => 'Terjadwal',
            'catatan_status' => 'Jadwal survey lokasi telah dikonfirmasi untuk tanggal 20 Mei 2025.',
            'diajukan_pada' => 'Kemarin, 15.20',
        ]);

        $p3 = Pengajuan::create([
            'id' => 'SR-2025-0146',
            'nama_pengembang' => 'PT Priangan Griya Graha',
            'nama_perumahan' => 'Pesona Cibeureum Pratama',
            'lokasi' => 'Kec. Cibeureum, Kota Tasikmalaya',
            'jumlah_unit' => '120 unit rumah tapak',
            'luas_kawasan' => '26.000 m²',
            'penanggung_jawab' => 'Ir. Tatan Rustandi',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
            'catatan_status' => 'Dokumen persyaratan lengkap dan siap diperiksa tim administratif.',
            'diajukan_pada' => '12 Mei 2025',
        ]);

        $p4 = Pengajuan::create([
            'id' => 'SR-2025-0145',
            'nama_pengembang' => 'PT Galunggung Asri Propertindo',
            'nama_perumahan' => 'Bungursari Harmoni Indah',
            'lokasi' => 'Kec. Bungursari, Kota Tasikmalaya',
            'jumlah_unit' => '96 unit rumah tapak',
            'luas_kawasan' => '21.200 m²',
            'penanggung_jawab' => 'Yudi Permana, S.T.',
            'tahap' => 'Monitoring',
            'status' => 'Final',
            'catatan_status' => 'Hasil monitoring lapangan telah berstatus Final.',
            'diajukan_pada' => '11 Mei 2025',
        ]);

        $p5 = Pengajuan::create([
            'id' => 'SR-2025-0144',
            'nama_pengembang' => 'PT Mitra Sejahtera Tasik',
            'nama_perumahan' => 'Tamansari Asri Residence',
            'lokasi' => 'Kec. Tamansari, Kota Tasikmalaya',
            'jumlah_unit' => '160 unit rumah tapak',
            'luas_kawasan' => '38.000 m²',
            'penanggung_jawab' => 'Ahmad Fauzi, S.T.',
            'tahap' => 'Persetujuan',
            'status' => 'Siap disetujui',
            'catatan_status' => 'Seluruh tahapan verifikasi teknis dan monitoring telah terpenuhi.',
            'diajukan_pada' => '10 Mei 2025',
        ]);

        $kecamatanTasik = [
            'Kec. Mangkubumi, Kota Tasikmalaya',
            'Kec. Kawalu, Kota Tasikmalaya',
            'Kec. Cibeureum, Kota Tasikmalaya',
            'Kec. Tamansari, Kota Tasikmalaya',
            'Kec. Bungursari, Kota Tasikmalaya',
            'Kec. Indihiang, Kota Tasikmalaya',
            'Kec. Purbaratu, Kota Tasikmalaya',
            'Kec. Cihideung, Kota Tasikmalaya',
            'Kec. Cipedes, Kota Tasikmalaya',
            'Kec. Tawang, Kota Tasikmalaya',
        ];

        // Seed 37 additional dummy items for pagination demo (Total 42)
        for ($i = 143; $i >= 107; $i--) {
            $prefix = ['Griya ', 'Pesona ', 'Cluster ', 'Puri ', 'Taman '][rand(0, 4)];
            Pengajuan::create([
                'id' => "SR-2025-0{$i}",
                'nama_pengembang' => "PT Pengembang Mitra Tasik {$i}",
                'nama_perumahan' => "{$prefix}Tasik Indah {$i}",
                'lokasi' => $kecamatanTasik[rand(0, count($kecamatanTasik) - 1)],
                'jumlah_unit' => rand(50, 200) . " unit",
                'luas_kawasan' => rand(10, 50) . ".000 m²",
                'penanggung_jawab' => "Penanggung Jawab {$i}, S.T.",
                'tahap' => ['Dokumen', 'Verifikasi teknis', 'Survey', 'Monitoring', 'Persetujuan'][rand(0, 4)],
                'status' => ['Menunggu verifikasi', 'Perlu perbaikan', 'Terjadwal', 'Draft', 'Final', 'Siap disetujui'][rand(0, 5)],
                'catatan_status' => 'Pengajuan dalam proses verifikasi reguler.',
                'diajukan_pada' => '05 Mei 2025',
            ]);
        }

        // 3. Documents for SR-2025-0148
        $dokPerusahaan = [
            ['NIB dan Izin Usaha', 'nib_dan_izin_usaha.pdf', '08 Mei 2025 · 0.8 MB', 'Sesuai', ''],
            ['NPWP Perusahaan', 'npwp_perusahaan.pdf', '08 Mei 2025 · 1.0 MB', 'Belum diperiksa', ''],
            ['Akta Pendirian Perusahaan', 'akta_pendirian_perusahaan.pdf', '08 Mei 2025 · 1.1 MB', 'Sesuai', ''],
            ['KTP Direktur Utama', 'ktp_direktur_utama.pdf', '08 Mei 2025 · 1.3 MB', 'Perlu perbaikan', 'Scan KTP buram dan tanggal berlaku perlu dikonfirmasi.'],
            ['Surat Kuasa Penanggung Jawab', 'surat_kuasa_penanggung_jawab.pdf', '08 Mei 2025 · 1.4 MB', 'Sesuai', ''],
        ];

        foreach ($dokPerusahaan as $d) {
            DokumenPengajuan::create([
                'pengajuan_id' => 'SR-2025-0148',
                'kategori' => 'perusahaan',
                'nama_persyaratan' => $d[0],
                'nama_file' => $d[1],
                'ukuran_file' => $d[2],
                'tanggal_unggah' => '08 Mei 2025',
                'status' => $d[3],
                'catatan' => $d[4],
            ]);
        }

        $dokPerumahan = [
            ['Site Plan yang Disahkan', 'site_plan_yang_disahkan.pdf', '08 Mei 2025 · 0.8 MB', 'Sesuai', ''],
            ['Bukti Kepemilikan Tanah', 'bukti_kepemilikan_tanah.pdf', '08 Mei 2025 · 1.0 MB', 'Belum diperiksa', ''],
            ['Izin Lokasi', 'izin_lokasi.pdf', '08 Mei 2025 · 1.1 MB', 'Sesuai', ''],
            ['Persetujuan Lingkungan', 'persetujuan_lingkungan.pdf', '08 Mei 2025 · 1.3 MB', 'Perlu perbaikan', 'Pernyataan pengelolaan limbah cair belum melampirkan izin TPS terpadu.'],
            ['Izin PBG', 'izin_pbg.pdf', '08 Mei 2025 · 1.4 MB', 'Sesuai', ''],
            ['Gambar Rencana Tapak', 'gambar_rencana_tapak.pdf', '08 Mei 2025 · 1.6 MB', 'Sesuai', ''],
            ['Rencana Utilitas', 'rencana_utilitas.pdf', '08 Mei 2025 · 1.7 MB', 'Sesuai', ''],
            ['Rencana Drainase', 'rencana_drainase.pdf', '08 Mei 2025 · 1.9 MB', 'Sesuai', ''],
            ['Surat Pernyataan Pengembang', 'surat_pernyataan_pengembang.pdf', '08 Mei 2025 · 2.0 MB', 'Sesuai', ''],
            ['Daftar Unit Perumahan', 'daftar_unit_perumahan.pdf', '08 Mei 2025 · 2.1 MB', 'Sesuai', ''],
            ['Dokumen Analisis Dampak Lalu Lintas', 'dokumen_analisis_dampak_lalu_lintas.pdf', '08 Mei 2025 · 2.3 MB', 'Sesuai', ''],
        ];

        foreach ($dokPerumahan as $d) {
            DokumenPengajuan::create([
                'pengajuan_id' => 'SR-2025-0148',
                'kategori' => 'perumahan',
                'nama_persyaratan' => $d[0],
                'nama_file' => $d[1],
                'ukuran_file' => $d[2],
                'tanggal_unggah' => '08 Mei 2025',
                'status' => $d[3],
                'catatan' => $d[4],
            ]);
        }

        DokumenPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'kategori' => 'paket_teknis',
            'nama_persyaratan' => 'Paket Dokumen Teknis',
            'nama_file' => 'Paket_tekdok_GriyaMahardika.zip',
            'ukuran_file' => '24.8 MB · berisi 7 berkas',
            'tanggal_unggah' => '08 Mei 2025',
            'status' => 'Sesuai',
            'catatan' => 'Mencakup 21 kategori teknis opsional.',
        ]);

        // 4. Surveys for SR-2025-0148
        SurveyPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'tanggal_survey' => '2025-05-22',
            'jam_survey' => '09.00 WIB',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'petugas_role' => 'Perwaskim',
            'instruksi' => 'Tinjau kesesuaian site plan, drainase, akses jalan, dan fasilitas umum di lokasi perumahan.',
            'status' => 'Terjadwal',
        ]);

        SurveyPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'tanggal_survey' => '2025-05-20',
            'jam_survey' => '09.00 WIB',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'petugas_role' => 'Perwaskim',
            'instruksi' => 'Survey awal lokasi perumahan.',
            'status' => 'Dijadwalkan Ulang',
        ]);

        // 5. Monitoring for SR-2025-0148
        MonitoringPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'status_monitoring' => 'Draft',
            'tanggal_survey' => '22 Mei 2025',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'hasil' => 'Perlu Evaluasi',
            'temuan' => 'Saluran drainase sisi timur belum sesuai detail rencana. Akses kendaraan pemadam perlu penegasan pada area tikungan blok C.',
            'kesimpulan' => 'Kawasan dapat dilanjutkan setelah perbaikan desain drainase dan penyampaian revisi gambar teknis.',
            'kesepakatan' => 'Pengembang akan mengunggah revisi dalam 7 hari kerja.',
            'rencana_tindak_lanjut' => 'RENCANA TINDAK LANJUT WAJIB: Perbarui gambar drainase dan lengkapi simulasi manuver kendaraan pemadam sebelum persetujuan dilanjutkan.',
            'foto_bukti' => [
                ['label' => 'Drainase sisi timur', 'bg' => '#e2e8f0'],
                ['label' => 'Akses blok C', 'bg' => '#e7dfd5'],
                ['label' => 'Ruang terbuka hijau', 'bg' => '#dbe5e7'],
            ],
        ]);

        // 6. Riwayat for SR-2025-0148
        RiwayatPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'judul' => '14 Mei 2025 · 10.42',
            'deskripsi' => 'Verifikator Disperwaskim mengirim permintaan perbaikan untuk 3 dokumen.',
            'oleh' => 'Verifikator Disperwaskim',
            'tanggal' => '14 Mei 2025 · 10.42',
        ]);

        RiwayatPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'judul' => '13 Mei 2025 · 15.10',
            'deskripsi' => 'Pengembang mengunggah versi 2 KTP Direktur Utama. Versi 1 tetap tersimpan.',
            'oleh' => 'PT Citra Tasik Mandiri',
            'tanggal' => '13 Mei 2025 · 15.10',
        ]);

        RiwayatPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'judul' => '08 Mei 2025 · 09.21',
            'deskripsi' => 'Pengajuan baru dibuat oleh PT Citra Tasik Mandiri.',
            'oleh' => 'PT Citra Tasik Mandiri',
            'tanggal' => '08 Mei 2025 · 09.21',
        ]);

        // 7. Notifikasi
        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0148',
            'judul' => 'Pengajuan baru',
            'pesan' => 'Griya Mangkubumi Asri',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0144',
            'judul' => 'Permintaan perbaikan telah terkirim',
            'pesan' => 'Tamansari Asri Residence',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now()->subHours(2),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0146',
            'judul' => 'Survey ditugaskan',
            'pesan' => 'Pesona Cibeureum Pratama',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now()->subHours(4),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0145',
            'judul' => 'Monitoring final tersedia',
            'pesan' => 'Puri Cendana',
            'tipe' => 'read',
            'is_read' => true,
            'created_at' => now()->subDays(1),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0147',
            'judul' => 'Dokumen persetujuan akhir tersedia',
            'pesan' => 'Taman Kencana Asri',
            'tipe' => 'read',
            'is_read' => true,
            'created_at' => now()->subDays(2),
        ]);
    }
}
