<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('UserController', function () {
    describe('GET /users', function () {
        it('lists users with filters and pagination', function () {
            User::factory()->count(25)->create();

            $admin = User::factory()->create(['email_verified_at' => now()]);
            $token = $admin->createToken('api')->plainTextToken;
            $response = $this->getJson(route('users.index', ['filters[name]' => 'User', 'page' => 1, 'per_page' => 10]), [
                'Authorization' => 'Bearer '.$token,
            ]);

            $response->assertOk();
            $response->assertJsonStructure([
                'data', 'links', 'meta',
            ]);
        });
    });

    describe('POST /users', function () {
        it('creates a user as admin', function () {
            $admin = User::factory()->create(['email_verified_at' => now()]);

            $token = $admin->createToken('api')->plainTextToken;
            $payload = [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'Password123!',
            ];

            $response = $this->postJson(route('users.store'), $payload, [
                'Authorization' => 'Bearer '.$token,
            ]);

            $response->assertStatus(201);
            $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
        });
    });

    describe('PUT /users/{id}', function () {
        it('updates a user as admin', function () {
            $admin = User::factory()->create(['email_verified_at' => now()]);

            $token = $admin->createToken('api')->plainTextToken;
            $user = User::factory()->create();
            $payload = ['name' => 'Updated Name'];

            $response = $this->putJson(route('users.update', $user->id), $payload, [
                'Authorization' => 'Bearer '.$token,
            ]);

            $response->assertOk();
            $response->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ]);

            $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        });
    });

    describe('DELETE /users/{id}', function () {
        it('deletes a user as admin', function () {
            $admin = User::factory()->create(['email_verified_at' => now()]);

            $token = $admin->createToken('api')->plainTextToken;

            $user = User::factory()->create();

            $response = $this->deleteJson(route('users.destroy', $user->id), [], [
                'Authorization' => 'Bearer '.$token,
            ]);

            $response->assertNoContent();
        });
    });
});
