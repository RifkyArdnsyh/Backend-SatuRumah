<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Mengisi pengaturan awal. Dijalankan manual:
 *   php artisan db:seed --class=SettingsSeeder
 * Angka SLA adalah USULAN AWAL dan menunggu penetapan resmi Disperwaskim.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'sla.t2_days' => [5, 'SLA Verifikasi Administrasi (hari kerja) - usulan awal, menunggu penetapan Disperwaskim'],
            'sla.t3_days' => [7, 'SLA Verifikasi Teknis (hari kerja) - usulan awal, menunggu penetapan Disperwaskim'],
            'sla.t4_days' => [7, 'SLA Survey Lapangan (hari kerja) - usulan awal, menunggu penetapan Disperwaskim'],
            'sla.t5_days' => [5, 'SLA Persetujuan (hari kerja) - usulan awal, menunggu penetapan Disperwaskim'],
            'sla.warning_percent' => [80, 'Persentase batas waktu untuk label Mendekati Batas'],
            'revision.deadline_days' => [14, 'Batas perbaikan pengembang (hari kalender)'],
            'upload.pdf_max_mb' => [10, 'Ukuran maksimal berkas PDF (MB)'],
            'upload.dwg_max_mb' => [50, 'Ukuran maksimal berkas DWG (MB)'],
            'upload.max_files_per_slot' => [10, 'Jumlah maksimal berkas pada slot banyak-berkas'],
            'upload.photo_max_mb' => [10, 'Ukuran maksimal foto lapangan (MB)'],
            'upload.photo_max_count' => [15, 'Jumlah maksimal foto per laporan'],
        ];

        foreach ($rows as $key => [$value, $description]) {
            Setting::updateOrCreate(['key' => $key], ['value' => (string) $value, 'description' => $description]);
        }
    }
}
