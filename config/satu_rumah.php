<?php

/*
 * Konfigurasi SATU RUMAH.
 * Nilai pada 'defaults' dipakai bila belum ada baris di tabel settings.
 * Angka SLA bersifat USULAN AWAL (BRD 4.5 / FSD BR-041) dan menunggu
 * penetapan resmi Disperwaskim; ubah lewat tabel settings atau .env.
 */

return [

    'roles' => ['administrator', 'verifikator', 'pengembang', 'tim_lapangan'],

    'defaults' => [
        // SLA (hari kerja) per tahap
        'sla.t2_days' => (int) env('SLA_T2_DAYS', 5),
        'sla.t3_days' => (int) env('SLA_T3_DAYS', 7),
        'sla.t4_days' => (int) env('SLA_T4_DAYS', 7),
        'sla.t5_days' => (int) env('SLA_T5_DAYS', 5),
        'sla.warning_percent' => (int) env('SLA_WARNING_PERCENT', 80),
        'revision.deadline_days' => (int) env('REVISION_DEADLINE_DAYS', 14),

        // Batas upload
        'upload.pdf_max_mb' => (int) env('UPLOAD_PDF_MAX_MB', 10),
        'upload.dwg_max_mb' => (int) env('UPLOAD_DWG_MAX_MB', 50),
        'upload.max_files_per_slot' => (int) env('UPLOAD_MAX_FILES_PER_SLOT', 10),
        'upload.photo_max_mb' => (int) env('UPLOAD_PHOTO_MAX_MB', 10),
        'upload.photo_max_count' => (int) env('UPLOAD_PHOTO_MAX_COUNT', 15),
    ],

    /*
     * Katalog 18 slot berkas (FSD 2.5).
     * stage: tahap verifikasi yang memeriksa slot (2 = Administrasi, 3 = Teknis).
     */
    'slots' => [
        'S2-01' => ['step' => 2, 'name' => 'KTP Elektronik Pemohon', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S2-02' => ['step' => 2, 'name' => 'Nomor Induk Berusaha (NIB)', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S2-03' => ['step' => 2, 'name' => 'NPWP Perusahaan', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S2-04' => ['step' => 2, 'name' => 'Bukti Keanggotaan Asosiasi (REI/APERSI)', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S2-05' => ['step' => 2, 'name' => 'Akta Pendirian/Perubahan dan SK Kemenkumham', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],

        'S3-01' => ['step' => 3, 'name' => 'Surat Permohonan Persetujuan', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-02' => ['step' => 3, 'name' => 'Informasi Intensitas Pemanfaatan Ruang / KKPR', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-03' => ['step' => 3, 'name' => 'Bukti Kepemilikan Lahan atas nama PT / Sertifikat Induk', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-04' => ['step' => 3, 'name' => 'Bukti Penyediaan Lahan TPU', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-05' => ['step' => 3, 'name' => 'Rekomendasi Teknis Dinas Terkait', 'formats' => ['pdf'], 'multiple' => true, 'stage' => 2],
        'S3-06' => ['step' => 3, 'name' => 'Persetujuan ANDALALIN', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-07' => ['step' => 3, 'name' => 'Rekomendasi Air Bersih', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-08' => ['step' => 3, 'name' => 'Rekomendasi Listrik PLN', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-09' => ['step' => 3, 'name' => 'Surat Pernyataan Kesanggupan Penyediaan dan Penyerahan PSU', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-10' => ['step' => 3, 'name' => 'Data Penanggung Jawab Dokumen Teknis', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],
        'S3-11' => ['step' => 3, 'name' => 'Proposal Rencana Pembangunan Perumahan', 'formats' => ['pdf'], 'multiple' => false, 'stage' => 2],

        'S4-01' => ['step' => 4, 'name' => 'Dokumen Perencanaan dan Perancangan Site Plan', 'formats' => ['dwg', 'pdf'], 'multiple' => true, 'stage' => 3],
        'S4-02' => ['step' => 4, 'name' => 'Dokumen Kajian Teknis', 'formats' => ['pdf'], 'multiple' => true, 'stage' => 3],
    ],
];
