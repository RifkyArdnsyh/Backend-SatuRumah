<?php

namespace App\Services;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SuresmanTteService
{
    private string $baseUrl = 'https://suresman.tasikmalayakota.go.id/visualcustom';

    private function headers(): array
    {
        return [
            'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Referer'         => $this->baseUrl,
            'Origin'          => 'https://suresman.tasikmalayakota.go.id',
        ];
    }

    /**
     * Mengambil gambar TTE dan menyimpannya di storage lokal.
     * Mengembalikan relative path di disk public (misal: 'tte/tte_1990...png')
     * atau absolute path di server jika $absolute = true.
     */
    public function getOrDownloadTte(string $nip, ?string $overrideNama = null, ?string $overrideJabatan = null, bool $absolute = false): ?string
    {
        $cleanNip = preg_replace('/\D/', '', $nip);
        if (strlen($cleanNip) < 9) {
            Log::warning("SuresmanTteService: NIP minimal 9 digit. Diberikan: {$nip}");
            return null;
        }

        $relativePath = "tte/tte_{$cleanNip}.png";

        // 1. Cek Cache Lokal agar tidak spam request ke Suresman
        if (Storage::disk('public')->exists($relativePath)) {
            return $absolute 
                ? Storage::disk('public')->path($relativePath) 
                : $relativePath;
        }

        try {
            $jar = new CookieJar();
            $headers = $this->headers();

            // Step 1: Ambil Session Cookie
            Http::withoutVerifying()
                ->withHeaders($headers)
                ->withOptions(['cookies' => $jar])
                ->timeout(10)
                ->get($this->baseUrl);

            // Step 2: Form Post Pencarian NIP
            $searchResponse = Http::withoutVerifying()
                ->withHeaders($headers)
                ->withOptions(['cookies' => $jar, 'allow_redirects' => true])
                ->timeout(12)
                ->asForm()
                ->post($this->baseUrl . '/search', ['nip' => $cleanNip]);

            $html = $searchResponse->body();
            preg_match('/name="name"\s+value="([^"]+)"/i', $html, $mName);
            preg_match('/name="position"\s+value="([^"]+)"/i', $html, $mPos);
            preg_match('/name="category"\s+value="([^"]+)"/i', $html, $mCat);
            preg_match('/name="rank_text"\s+value="([^"]+)"/i', $html, $mRank);

            $nama = $overrideNama ?: ($mName[1] ?? '');
            $jabatan = $overrideJabatan ?: ($mPos[1] ?? '');

            if (!$nama && !$jabatan) {
                Log::warning("SuresmanTteService: Pegawai dengan NIP {$cleanNip} tidak ditemukan di Suresman.");
                return null;
            }

            // Step 3: Request Download Binary PNG
            $downloadResponse = Http::withoutVerifying()
                ->withHeaders($headers)
                ->withOptions(['cookies' => $jar, 'allow_redirects' => true])
                ->timeout(15)
                ->asForm()
                ->post($this->baseUrl . '/download', [
                    'nip'       => $cleanNip,
                    'name'      => $nama,
                    'position'  => $jabatan,
                    'category'  => $mCat[1] ?? '',
                    'rank_text' => $mRank[1] ?? '',
                ]);

            if (str_contains($downloadResponse->header('Content-Type') ?? '', 'image')) {
                Storage::disk('public')->put($relativePath, $downloadResponse->body());

                return $absolute 
                    ? Storage::disk('public')->path($relativePath) 
                    : $relativePath;
            }

            Log::error("SuresmanTteService: Respons bukan gambar untuk NIP {$cleanNip}.");
            return null;
        } catch (\Throwable $e) {
            Log::error("SuresmanTteService Exception: " . $e->getMessage());
            return null;
        }
    }
}

