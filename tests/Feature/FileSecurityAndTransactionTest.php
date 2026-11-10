<?php

namespace Tests\Feature;

use App\Models\DokumenPengajuan;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileSecurityAndTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /**
     * a. Verifikasi penolakan upload file palsu (Spoofed Extension vs Magic Bytes).
     */
    public function test_rejects_fake_spoofed_pdf_and_dwg_files(): void
    {
        $dev = User::factory()->create(['role' => 'pengembang']);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-9001',
            'user_id' => $dev->id,
            'nama_pengembang' => 'PT Cipta Rumah',
            'nama_perumahan' => 'Griya Asri',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '20',
            'luas_kawasan' => '5000',
            'penanggung_jawab' => 'Rian',
            'tahap' => 'Dokumen',
            'status' => 'Perlu perbaikan',
        ]);

        // 1. Buat file teks/php yang dinamai .pdf palsu (tanpa header %PDF-)
        $fakePdf = UploadedFile::fake()->createWithContent('malicious.pdf', '<?php phpinfo(); ?>');

        $res1 = $this->actingAs($dev, 'sanctum')->postJson("/api/v1/pengajuan/{$pengajuan->id}/dokumen", [
            'kategori' => 'perusahaan',
            'nama_persyaratan' => 'KTP Direktur',
            'berkas' => $fakePdf,
        ]);

        $res1->assertStatus(422)
             ->assertJsonValidationErrors(['berkas']);

        // 2. Buat file palsu yang dinamai .dwg (tanpa header AutoCAD AC10xx)
        $fakeDwg = UploadedFile::fake()->createWithContent('siteplan.dwg', 'PLAIN TEXT FILE NOT CAD');

        $res2 = $this->actingAs($dev, 'sanctum')->postJson("/api/v1/pengajuan/{$pengajuan->id}/dokumen", [
            'kategori' => 'paket_teknis',
            'nama_persyaratan' => 'Site Plan AutoCAD',
            'berkas' => $fakeDwg,
        ]);

        $res2->assertStatus(422)
             ->assertJsonValidationErrors(['berkas']);
    }

    /**
     * Verifikasi lolos upload file yang valid dengan magic bytes yang sesuai.
     */
    public function test_accepts_valid_pdf_and_dwg_with_valid_magic_bytes(): void
    {
        $dev = User::factory()->create(['role' => 'pengembang']);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-9002',
            'user_id' => $dev->id,
            'nama_pengembang' => 'PT Cipta Rumah',
            'nama_perumahan' => 'Griya Asri',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '20',
            'luas_kawasan' => '5000',
            'penanggung_jawab' => 'Rian',
            'tahap' => 'Dokumen',
            'status' => 'Perlu perbaikan',
        ]);

        // Valid PDF magic bytes
        $validPdf = UploadedFile::fake()->createWithContent('legalitas.pdf', "%PDF-1.4\nValid PDF body contents...");

        $res = $this->actingAs($dev, 'sanctum')->postJson("/api/v1/pengajuan/{$pengajuan->id}/dokumen", [
            'kategori' => 'perusahaan',
            'nama_persyaratan' => 'NIB Legalitas',
            'berkas' => $validPdf,
        ]);

        $res->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Berkas berhasil diunggah ke storage privat.',
            ]);

        // Pastikan tersimpan di storage lokal (privat)
        $doc = DokumenPengajuan::first();
        $this->assertNotNull($doc);
        Storage::disk('local')->assertExists($doc->nama_file);
    }

    /**
     * b. Pembatasan akses download dokumen privat (Developer lain mendapat 403 Forbidden).
     */
    public function test_unauthorized_developer_cannot_download_private_documents(): void
    {
        $devOwner = User::factory()->create(['role' => 'pengembang']);
        $devAttacker = User::factory()->create(['role' => 'pengembang']);
        $verifikator = User::factory()->create(['role' => 'verifikator']);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-9003',
            'user_id' => $devOwner->id,
            'nama_pengembang' => 'PT Pemilik',
            'nama_perumahan' => 'Griya Pemilik',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '10',
            'luas_kawasan' => '2000',
            'penanggung_jawab' => 'Owner',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        $filePath = "dokumen/{$pengajuan->id}/secret_document.pdf";
        Storage::disk('local')->put($filePath, "%PDF-1.4 confidential content");

        $dokumen = DokumenPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'kategori' => 'perusahaan',
            'nama_persyaratan' => 'KTP Rahasia',
            'nama_file' => $filePath,
            'ukuran_file' => '10 KB',
            'status' => 'Sesuai',
        ]);

        // 1. Developer lain mencoba download berkas privat -> 403 Forbidden
        $resAttacker = $this->actingAs($devAttacker, 'sanctum')->getJson("/api/v1/dokumen/{$dokumen->id}/download");
        $resAttacker->assertStatus(403);

        // 2. Pemilik sah mendownload berkas -> 200 OK
        $resOwner = $this->actingAs($devOwner, 'sanctum')->get("/api/v1/dokumen/{$dokumen->id}/download");
        $resOwner->assertStatus(200);

        // 3. Verifikator Disperwaskim mendownload berkas -> 200 OK
        $resVerifikator = $this->actingAs($verifikator, 'sanctum')->get("/api/v1/dokumen/{$dokumen->id}/download");
        $resVerifikator->assertStatus(200);
    }

    /**
     * c. Verifikasi Rollback otomatis DB::transaction jika pencatatan RiwayatPengajuan gagal.
     */
    public function test_database_transaction_rollbacks_on_failure(): void
    {
        $verifikator = User::factory()->create([
            'name' => 'Verifikator Disperwaskim',
            'role' => 'verifikator',
        ]);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-9004',
            'nama_pengembang' => 'PT Atomik Test',
            'nama_perumahan' => 'Perumahan Atomik',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '30',
            'luas_kawasan' => '6000',
            'penanggung_jawab' => 'Ahmad',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        // Simulasikan error/exception saat RiwayatPengajuan hendak dibuat
        RiwayatPengajuan::saving(function () {
            throw new \Exception('Simulasi kegagalan pencatatan audit log database.');
        });

        try {
            $this->actingAs($verifikator)->post("/pengajuan/{$pengajuan->id}/status", [
                'status' => 'Perlu perbaikan',
                'tahap' => 'Verifikasi teknis',
            ]);
        } catch (\Exception $e) {
            // Tangkap exception
        }

        // Pastikan status pengajuan TIDAK terubah di database (Rollback bekerja)
        $pengajuan->refresh();
        $this->assertEquals('Menunggu verifikasi', $pengajuan->status);
        $this->assertEquals('Dokumen', $pengajuan->tahap);
    }
}

