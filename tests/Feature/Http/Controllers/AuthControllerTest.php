<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

describe('AuthController', function () {
    describe('POST /auth/login', function () {
        it('logs in and logs out successfully', function () {
            $user = User::factory()->create([
                'password' => 'password123',
                'email_verified_at' => now(),
            ]);

            $login = $this->postJson(route('auth.login'), [
                'email' => $user->email,
                'password' => 'password123',
            ]);

            $login->assertOk();
            $token = $login->json('token');
            expect($token)->not->toBeEmpty();

            $logout = $this->postJson(route('auth.logout'), [], ['Authorization' => 'Bearer '.$token]);
            $logout->assertNoContent();
        });
    });
});

describe('AccountController', function () {
    describe('POST /account/register', function () {
        it('registers a user', function () {
            $userData = [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'SecurePass123!',
            ];

            $response = $this->postJson(route('account.register'), $userData);

            $response->assertCreated();
            $response->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ]);

            $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        });
    });

    describe('POST /account/forgot-password', function () {
        it('sends reset password notification and allows password reset', function () {
            Notification::fake();

            $user = User::factory()->create();

            $response = $this->postJson(route('account.forgotPassword'), [
                'email' => $user->email,
            ]);

            $response->assertOk();
            Notification::assertSentTo($user, Illuminate\Auth\Notifications\ResetPassword::class);
        });
    });
});
