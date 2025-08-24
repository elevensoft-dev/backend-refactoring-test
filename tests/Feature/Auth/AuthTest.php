<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations, CreatesPassportClients;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function login_with_valid_credentials_returns_ok(): void
    {
        // Arrange
        $user = User::factory()->createOne(['email' => 'teste@teste.com', 'password' => '123456']);
        $data = [
            'email' => $user->email,
            'password' => '123456'
        ];
        // Act
        $response = $this->postJson('/api/auth/login', $data);
        // $responseBody = json_decode($response->getContent(), true);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }
    #[Test]
    public function login_with_invalid_credentials_returns_unauthorized(): void
    {
        // Arrange
        $data = [
            'email' => 'admin@admin.com',
            'password' => '1234567'
        ];
        // Act
        $response = $this->postJson('/api/auth/login', $data);
        // Assert
        $response->assertStatus(401);
    }
    #[Test]
    public function me_returns_authenticated_user(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        // Act
        $response = $this->getJson('/api/auth/me');
        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }
    #[Test]
    public function logout_revokes_token(): void
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        // Act
        $response = $this->postJson('/api/auth/logout');
        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logout realizado com sucesso',
        ]);
    }
}
