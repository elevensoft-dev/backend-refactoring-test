<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('AccountController', function () {
    describe('POST /account/register', function () {
        it('registers an account', function () {
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
