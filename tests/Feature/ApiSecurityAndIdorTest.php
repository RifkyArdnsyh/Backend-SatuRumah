<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiSecurityAndIdorTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_cannot_access_protected_api_routes(): void
    {
        $response = $this->getJson('/api/v1/pengajuan');
        $response->assertStatus(401)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Unauthenticated.'
                 ]);
    }

    public function test_auth_login_returns_sanctum_bearer_token(): void
    {
        $developer = User::factory()->create([
            'email' => 'dev@saturumah.com',
            'password' => bcrypt('secret123'),
            'role' => 'pengembang',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'dev@saturumah.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'token',
                         'token_type',
                         'user' => ['id', 'name', 'email', 'role']
                     ]
                 ]);
    }

    public function test_developer_cannot_view_other_developer_pengajuan_idor_guard(): void
    {
        $dev1 = User::factory()->create(['role' => 'pengembang']);
        $dev2 = User::factory()->create(['role' => 'pengembang']);

        $pengajuan1 = Pengajuan::create([
            'id' => 'SR-2026-0001',
            'user_id' => $dev1->id,
            'nama_pengembang' => 'PT Dev Satu',
            'nama_perumahan' => 'Perumahan Satu',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '50',
            'luas_kawasan' => '10000',
            'penanggung_jawab' => 'Budi',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        // Dev 2 mencoba akses pengajuan milik Dev 1 (IDOR attempt)
        $response = $this->actingAs($dev2, 'sanctum')->getJson("/api/v1/pengajuan/{$pengajuan1->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'status' => 'error',
                 ]);
    }

    public function test_developer_can_view_own_pengajuan(): void
    {
        $dev1 = User::factory()->create(['role' => 'pengembang']);

        $pengajuan1 = Pengajuan::create([
            'id' => 'SR-2026-0001',
            'user_id' => $dev1->id,
            'nama_pengembang' => 'PT Dev Satu',
            'nama_perumahan' => 'Perumahan Satu',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '50',
            'luas_kawasan' => '10000',
            'penanggung_jawab' => 'Budi',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        $response = $this->actingAs($dev1, 'sanctum')->getJson("/api/v1/pengajuan/{$pengajuan1->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => 'SR-2026-0001',
                     ]
                 ]);
    }

    public function test_verifikator_can_view_any_pengajuan(): void
    {
        $dev = User::factory()->create(['role' => 'pengembang']);
        $verifikator = User::factory()->create(['role' => 'verifikator']);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-0002',
            'user_id' => $dev->id,
            'nama_pengembang' => 'PT Dev Satu',
            'nama_perumahan' => 'Perumahan Dua',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '50',
            'luas_kawasan' => '10000',
            'penanggung_jawab' => 'Budi',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        $response = $this->actingAs($verifikator, 'sanctum')->getJson("/api/v1/pengajuan/{$pengajuan->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'data' => [
                         'id' => 'SR-2026-0002',
                     ]
                 ]);
    }

    public function test_developer_cannot_call_verifikasi_berkas_or_approve_sk(): void
    {
        $dev = User::factory()->create(['role' => 'pengembang']);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-0003',
            'user_id' => $dev->id,
            'nama_pengembang' => 'PT Dev Satu',
            'nama_perumahan' => 'Perumahan Tiga',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '50',
            'luas_kawasan' => '10000',
            'penanggung_jawab' => 'Budi',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        // Coba verifikasi berkas
        $res1 = $this->actingAs($dev, 'sanctum')->postJson("/api/v1/pengajuan/{$pengajuan->id}/verifikasi-berkas");
        $res1->assertStatus(403);

        // Coba approve SK
        $res2 = $this->actingAs($dev, 'sanctum')->postJson("/api/v1/pengajuan/{$pengajuan->id}/approve-sk");
        $res2->assertStatus(403);
    }

    public function test_verifikator_can_update_pengajuan_status(): void
    {
        $dev = User::factory()->create(['role' => 'pengembang']);
        $verifikator = User::factory()->create([
            'name' => 'Budi Verifikator',
            'role' => 'verifikator',
        ]);

        $pengajuan = Pengajuan::create([
            'id' => 'SR-2026-0004',
            'user_id' => $dev->id,
            'nama_pengembang' => 'PT Dev Empat',
            'nama_perumahan' => 'Perumahan Empat',
            'lokasi' => 'Tasikmalaya',
            'jumlah_unit' => '30',
            'luas_kawasan' => '8000',
            'penanggung_jawab' => 'Riko',
            'tahap' => 'Dokumen',
            'status' => 'Menunggu verifikasi',
        ]);

        $response = $this->actingAs($verifikator)->post("/pengajuan/{$pengajuan->id}/status", [
            'status' => 'Perlu perbaikan',
            'tahap' => 'Verifikasi teknis',
        ]);

        $response->assertStatus(302); // Redirect back with success

        $pengajuan->refresh();
        $this->assertEquals('Perlu perbaikan', $pengajuan->status);
        $this->assertEquals('Verifikasi teknis', $pengajuan->tahap);
    }
}

