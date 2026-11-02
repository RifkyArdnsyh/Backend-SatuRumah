<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'PT Tasik Indah Sentosa',
            'username' => 'pt_tasik_indah',
            'email' => 'pt@tasikindah.com',
            'password' => Hash::make('demo123'),
            'role' => 'pengembang',
            'avatar_initials' => 'PT',
        ]);
    }

    public function test_login_validation_error_returns_422(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validasi gagal.',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['username', 'password'],
            ]);
    }

    public function test_login_invalid_credentials_returns_401(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'pt_tasik_indah',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Username atau password salah.',
            ]);
    }

    public function test_login_success_returns_token_and_user_data(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'pt_tasik_indah',
            'password' => 'demo123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login berhasil.',
                'role' => 'pengembang',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'role',
                'user' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'role',
                    'avatar_initials',
                ],
            ]);
    }

    public function test_login_with_email_also_works(): void
    {
        $response = $this->postJson('/api/login', [
            'username' => 'pt@tasikindah.com',
            'password' => 'demo123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'role' => 'pengembang',
            ]);
    }

    public function test_logout_unauthenticated_returns_401(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_logout_authenticated_returns_200(): void
    {
        $user = User::where('username', 'pt_tasik_indah')->first();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);

        // Token should be revoked
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}

