<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::actingAs(User::factory()->create());
    }

    public function test_index_endpoint_returns_paginated_users()
    {
        User::factory()->count(6)->create();
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_index_endpoint_can_filter_by_name()
    {
        User::factory()->create(['name' => 'Testing Index']);
        User::factory()->count(4)->create();

        $response = $this->getJson('/api/users?name=Testing');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Testing Index');
    }

    public function test_show_endpoint_returns_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_show_endpoint_returns_404_for_non_existent_user()
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404);
    }

    public function test_store_endpoint_creates_a_user()
    {
        $userData = [
            'name' => 'Testing Store',
            'email' => 'testing@sto.re',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Testing Store');

        $this->assertDatabaseHas('users', [
            'email' => 'testing@sto.re',
        ]);
    }

    public function test_store_endpoint_returns_validation_errors()
    {
        $userData = [
            'name' => '',
            'email' => 'te.st',
            'password' => '2short',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(array_keys($userData));
    }

    public function test_update_endpoint_updates_a_user()
    {
        $user = User::factory()->create();
        $updatedData = [
            'name' => 'Test Update',
            'email' => 'test@upda.te',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Test Update');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test Update',
            'email' => 'test@upda.te',
        ]);
    }

    public function test_update_endpoint_returns_validation_errors_for_invalid_email()
    {
        $user = User::factory()->create();
        $invalidData = [
            'name' => '',
            'email' => 'te.st',
            'password' => '2short',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(array_keys($invalidData));
    }

    public function test_destroy_endpoint_deletes_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
