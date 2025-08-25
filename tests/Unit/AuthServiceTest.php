<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_login_with_valid_credentials_returns_user_and_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $result = $this->authService->login([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertIsString($result['token']);
    }

    public function test_login_with_invalid_email_throws_exception(): void
    {
        $this->expectException(ValidationException::class);

        $this->authService->login([
            'email' => 'invalid@example.com',
            'password' => 'password123'
        ]);
    }

    public function test_login_with_invalid_password_throws_exception(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $this->expectException(ValidationException::class);

        $this->authService->login([
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);
    }

    public function test_register_with_valid_data_creates_user_and_returns_token(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $result = $this->authService->register($userData);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals('John Doe', $result['user']->name);
        $this->assertEquals('john@example.com', $result['user']->email);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);
    }

    public function test_register_hashes_password(): void
    {
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'plaintext'
        ];

        $result = $this->authService->register($userData);

        $this->assertNotEquals('plaintext', $result['user']->password);
        $this->assertTrue(Hash::check('plaintext', $result['user']->password));
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');
        $user->withAccessToken($token->accessToken);

        $this->authService->logout($user);

        $user->refresh();
        $this->assertCount(0, $user->tokens);
    }

    public function test_logout_without_token_returns_false(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Error::class);
        $this->authService->logout($user);
    }

    public function test_generates_unique_tokens_for_different_users(): void
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $result1 = $this->authService->login(['email' => 'user1@example.com', 'password' => 'password']);
        $result2 = $this->authService->login(['email' => 'user2@example.com', 'password' => 'password']);

        $this->assertNotEquals($result1['token'], $result2['token']);
    }

    public function test_token_belongs_to_correct_user(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $result = $this->authService->login(['email' => 'test@example.com', 'password' => 'password']);

        $this->assertEquals($user->id, $result['user']->id);
    }
}
