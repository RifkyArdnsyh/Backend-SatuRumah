<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Validasi berkas pengajuan (FSD BR-018 s.d. BR-024, SRS REQ-F-018/019).
 * Memeriksa ekstensi, ukuran, dan ISI berkas (bukan hanya ekstensi).
 * Catatan: pemindaian malware (REQ-NF-023) belum termasuk, ditambahkan terpisah (mis. ClamAV).
 */
class SubmissionFileValidator
{
    /**
     * @param  int  $existingCurrentFiles  jumlah berkas aktif yang sudah ada pada slot
     * @return array<int, array{code: string, message: string}> kosong bila lolos
     */
    public function validate(UploadedFile $file, string $slotCode, int $existingCurrentFiles = 0): array
    {
        $slot = config("satu_rumah.slots.{$slotCode}");
        if (!$slot) {
            return [$this->err('ERR-002', 'Slot berkas tidak dikenal.')];
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = $slot['formats'];

        if (!in_array($ext, $allowed, true)) {
            return [$this->err('ERR-002', 'Format tidak sesuai. Slot ini hanya menerima ' . $this->formatLabel($allowed) . '.')];
        }

        // Ukuran
        $maxMb = $ext === 'dwg'
            ? Setting::getInt('upload.dwg_max_mb', 50)
            : Setting::getInt('upload.pdf_max_mb', 10);

        if ($file->getSize() > $maxMb * 1024 * 1024) {
            return [$this->err('ERR-001', "Ukuran berkas melebihi {$maxMb} MB. Kecilkan berkas lalu unggah kembali.")];
        }

        // Jumlah berkas pada slot
        $maxFiles = Setting::getInt('upload.max_files_per_slot', 10);
        if ($slot['multiple'] && $existingCurrentFiles >= $maxFiles) {
            return [$this->err('ERR-002', "Slot ini menerima maksimal {$maxFiles} berkas.")];
        }

        // Isi berkas
        $path = $file->getRealPath();
        $head = (string) @file_get_contents($path, false, null, 0, 16);

        if ($ext === 'pdf') {
            return $this->checkPdf($path, $head);
        }

        // DWG: kode versi AutoCAD diawali "AC10xx" (2000 ke atas) atau "AC1.x"/"AC2.x" (lama)
        if (!preg_match('/^AC(10|1\.|2\.)/', $head)) {
            return [$this->err('ERR-003', 'Berkas tidak dapat dibaca atau bukan berkas DWG yang valid. Gunakan berkas lain.')];
        }

        return [];
    }

    /**
     * Kelengkapan slot S4-01 (BR-020): minimal 1 DWG dan 1 PDF.
     *
     * @param  array<int, string>  $fileTypes  daftar tipe berkas aktif pada slot (mis. ['pdf','dwg'])
     * @return array<int, array{code: string, message: string}>
     */
    public function validateSitePlanSet(array $fileTypes): array
    {
        if (!in_array('dwg', $fileTypes, true) || !in_array('pdf', $fileTypes, true)) {
            return [$this->err('ERR-004', 'Site Plan wajib memuat minimal 1 berkas DWG dan 1 berkas PDF.')];
        }

        return [];
    }

    /** Nama berkas aman + pengenal unik (BR-022). */
    public function storedName(UploadedFile $file): string
    {
        return (string) Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
    }

    public function sanitizeOriginalName(string $name): string
    {
        $base = pathinfo($name, PATHINFO_FILENAME);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $base = preg_replace('/[^A-Za-z0-9._ -]/', '', $base) ?: 'berkas';

        return Str::limit(trim($base), 120, '') . ($ext ? '.' . strtolower($ext) : '');
    }

    /** @return array<int, array{code: string, message: string}> */
    private function checkPdf(string $path, string $head): array
    {
        if (!str_starts_with($head, '%PDF-')) {
            return [$this->err('ERR-003', 'Berkas tidak dapat dibaca atau bukan PDF yang valid. Gunakan berkas lain.')];
        }

        $size = filesize($path);
        $tail = (string) @file_get_contents($path, false, null, max(0, $size - 2048));
        if (!str_contains($tail, '%%EOF')) {
            return [$this->err('ERR-003', 'Berkas tidak dapat dibaca atau rusak. Gunakan berkas lain.')];
        }

        // PDF terkunci kata sandi memuat entri /Encrypt (BR-023)
        $content = (string) @file_get_contents($path);
        if (preg_match('/\/Encrypt\b/', $content)) {
            return [$this->err('ERR-003', 'Berkas tidak dapat dibaca atau dikunci kata sandi. Gunakan berkas lain.')];
        }

        return [];
    }

    /** @param array<int, string> $formats */
    private function formatLabel(array $formats): string
    {
        $labels = array_map('strtoupper', $formats);

        return count($labels) === 2 ? "{$labels[0]} dan {$labels[1]}" : $labels[0];
    }

    /** @return array{code: string, message: string} */
    private function err(string $code, string $message): array
    {
        return ['code' => $code, 'message' => $message];
    }
}
