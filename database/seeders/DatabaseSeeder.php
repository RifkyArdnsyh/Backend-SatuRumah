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

        // 2. Create Submissions (Pengajuan) - Synchronized with Mobile App (pengajuan_form_controller.dart)
        $p1 = Pengajuan::create([
            'id' => 'SR-2025-0148',
            'nama_pengembang' => 'PT Citra Tasik Mandiri',
            'nama_perumahan' => 'Griya Mangkubumi Asri',
            'lokasi' => 'Kec. Mangkubumi, Kota Tasikmalaya',
            'jumlah_unit' => '148 unit rumah tapak',
            'luas_kawasan' => '34.800 m²',
            'penanggung_jawab' => 'H. Asep Hendrayana, S.T.',
            'tahap' => 'Survey',
            'status' => 'Terjadwal',
            'catatan_status' => 'Seluruh dokumen persyaratan telah diverifikasi dan dinyatakan sesuai. Jadwal survey lapangan telah ditetapkan untuk tanggal 22 Mei 2025 pukul 09.00 WIB.',
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
            'catatan_status' => 'Jadwal survey lokasi telah dikonfirmasi untuk tanggal 20 Mei 2025. Titik kumpul Kantor Pemasaran Kawalu.',
            'diajukan_pada' => '12 Mei 2025 · 15.20 WIB',
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
            'catatan_status' => 'Dokumen persyaratan lengkap dan siap diperiksa tim administratif Disperwaskim.',
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
            'catatan_status' => 'Hasil monitoring lapangan telah berstatus Final dan Berita Acara telah diterbitkan.',
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

        $p6 = Pengajuan::create([
            'id' => 'SR-20260720-612',
            'nama_pengembang' => 'PT. Tasik Indah Sentosa',
            'nama_perumahan' => 'Green Tasik',
            'lokasi' => 'Kec. Cipedes, Kota Tasikmalaya',
            'jumlah_unit' => '85 unit rumah tapak',
            'luas_kawasan' => '15.000 m²',
            'penanggung_jawab' => 'H. Tatang Sutisna',
            'tahap' => 'Survey',
            'status' => 'Dalam proses',
            'catatan_status' => 'Pengajuan dalam proses survey lokasi perumahan.',
            'diajukan_pada' => '20 Juli 2026',
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

        // Seed 36 additional dummy items for pagination demo (Total 42 items)
        for ($i = 143; $i >= 108; $i--) {
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

        // 3. Document Definitions Template
        $dokPerusahaanTemplate = [
            ['NIB dan Izin Usaha', 'nib_dan_izin_usaha.pdf', '0.8 MB'],
            ['NPWP Perusahaan', 'npwp_perusahaan.pdf', '1.0 MB'],
            ['Akta Pendirian Perusahaan', 'akta_pendirian_perusahaan.pdf', '1.1 MB'],
            ['KTP Direktur Utama', 'ktp_direktur_utama.pdf', '1.3 MB'],
            ['Surat Kuasa Penanggung Jawab', 'surat_kuasa_penanggung_jawab.pdf', '1.4 MB'],
        ];

        $dokPerumahanTemplate = [
            ['Site Plan yang Disahkan', 'site_plan_yang_disahkan.pdf', '0.8 MB'],
            ['Bukti Kepemilikan Tanah', 'bukti_kepemilikan_tanah.pdf', '1.0 MB'],
            ['Izin Lokasi', 'izin_lokasi.pdf', '1.1 MB'],
            ['Persetujuan Lingkungan', 'persetujuan_lingkungan.pdf', '1.3 MB'],
            ['Izin PBG', 'izin_pbg.pdf', '1.4 MB'],
            ['Gambar Rencana Tapak', 'gambar_rencana_tapak.pdf', '1.6 MB'],
            ['Rencana Utilitas', 'rencana_utilitas.pdf', '1.7 MB'],
            ['Rencana Drainase', 'rencana_drainase.pdf', '1.9 MB'],
            ['Surat Pernyataan Pengembang', 'surat_pernyataan_pengembang.pdf', '2.0 MB'],
            ['Daftar Unit Perumahan', 'daftar_unit_perumahan.pdf', '2.1 MB'],
            ['Dokumen Analisis Dampak Lalu Lintas', 'dokumen_analisis_dampak_lalu_lintas.pdf', '2.3 MB'],
        ];

        // Seed documents for ALL submissions
        $allPengajuans = Pengajuan::all();
        foreach ($allPengajuans as $p) {
            foreach ($dokPerusahaanTemplate as $idx => $d) {
                DokumenPengajuan::create([
                    'pengajuan_id' => $p->id,
                    'kategori' => 'perusahaan',
                    'nama_persyaratan' => $d[0],
                    'nama_file' => strtolower(str_replace(' ', '_', $p->id)) . '_' . $d[1],
                    'ukuran_file' => '08 Mei 2025 · ' . $d[2],
                    'tanggal_unggah' => '08 Mei 2025',
                    'status' => 'Sesuai',
                    'catatan' => 'Dokumen lengkap dan telah diverifikasi sah.',
                ]);
            }

            foreach ($dokPerumahanTemplate as $idx => $d) {
                DokumenPengajuan::create([
                    'pengajuan_id' => $p->id,
                    'kategori' => 'perumahan',
                    'nama_persyaratan' => $d[0],
                    'nama_file' => strtolower(str_replace(' ', '_', $p->id)) . '_' . $d[1],
                    'ukuran_file' => '08 Mei 2025 · ' . $d[2],
                    'tanggal_unggah' => '08 Mei 2025',
                    'status' => 'Sesuai',
                    'catatan' => 'Memenuhi persyaratan teknis dan tata ruang.',
                ]);
            }

            DokumenPengajuan::create([
                'pengajuan_id' => $p->id,
                'kategori' => 'paket_teknis',
                'nama_persyaratan' => 'Paket Dokumen Teknis',
                'nama_file' => 'Paket_tekdok_' . preg_replace('/[^A-Za-z0-9]/', '', $p->nama_perumahan) . '.zip',
                'ukuran_file' => '24.8 MB · berisi 7 berkas',
                'tanggal_unggah' => '08 Mei 2025',
                'status' => 'Sesuai',
                'catatan' => 'Mencakup 21 kategori teknis opsional.',
            ]);
        }

        // 4. Surveys for SR-2025-0148 & SR-2025-0147 & SR-20260720-612
        SurveyPengajuan::create([
            'pengajuan_id' => 'SR-2025-0148',
            'tanggal_survey' => '2025-05-22',
            'jam_survey' => '09.00 WIB',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'petugas_role' => 'Perwaskim',
            'instruksi' => 'Tinjau kesesuaian site plan, drainase, akses jalan, dan fasilitas umum di lokasi perumahan (Kec. Mangkubumi).',
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

        SurveyPengajuan::create([
            'pengajuan_id' => 'SR-2025-0147',
            'tanggal_survey' => '2025-05-20',
            'jam_survey' => '09.00 WIB',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'petugas_role' => 'Perwaskim',
            'instruksi' => 'Jadwal survey lokasi telah dikonfirmasi. Titik kumpul Kantor Pemasaran Kawalu.',
            'status' => 'Terjadwal',
        ]);

        SurveyPengajuan::create([
            'pengajuan_id' => 'SR-20260720-612',
            'tanggal_survey' => '2026-06-20',
            'jam_survey' => '09.00 WIB',
            'petugas_nama' => 'Rahmat Hidayat, S.T.',
            'petugas_role' => 'Perwaskim',
            'instruksi' => 'Survey lapangan untuk perumahan Green Tasik.',
            'status' => 'Terjadwal',
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

        // 7. Notifikasi - Synchronized with Mobile notifikasi_provider.dart
        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0148',
            'judul' => 'Pengajuan baru',
            'pesan' => 'Griya Mangkubumi Asri (SR-2025-0148) telah diajukan dan menunggu verifikasi teknis.',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0144',
            'judul' => 'Permintaan perbaikan telah terkirim',
            'pesan' => 'Tamansari Asri Residence (SR-2025-0144) telah melengkapi verifikasi teknis.',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now()->subHours(2),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0146',
            'judul' => 'Survey ditugaskan',
            'pesan' => 'Pesona Cibeureum Pratama (SR-2025-0146) telah diagendakan survey lokasi.',
            'tipe' => 'unread',
            'is_read' => false,
            'created_at' => now()->subHours(4),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0145',
            'judul' => 'Monitoring final tersedia',
            'pesan' => 'Hasil monitoring Bungursari Harmoni Indah (SR-2025-0145) telah disahkan Final.',
            'tipe' => 'read',
            'is_read' => true,
            'created_at' => now()->subDays(1),
        ]);

        Notifikasi::create([
            'pengajuan_id' => 'SR-2025-0147',
            'judul' => 'Dokumen persetujuan akhir tersedia',
            'pesan' => 'Kencana Kawalu Regency (SR-2025-0147) siap untuk survey lokasi perwaskim.',
            'tipe' => 'read',
            'is_read' => true,
            'created_at' => now()->subDays(2),
        ]);
    }
}
