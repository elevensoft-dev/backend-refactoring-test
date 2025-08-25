<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_active_users_by_default(): void
    {
        User::factory()->count(3)->create();
        User::factory()->count(2)->create(['deleted_at' => now()]);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'deleted_at', 'created_at', 'updated_at']
                ],
                'meta' => [
                    'current_page',
                    'per_page',
                    'total'
                ]
            ])
            ->assertJsonCount(4, 'data');
    }

    public function test_can_list_only_trashed_users_with_parameter(): void
    {
        User::factory()->count(3)->create();
        User::factory()->count(2)->create(['deleted_at' => now()]);

        $response = $this->getJson('/api/users?trashed=true');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    ['deleted_at' => true], 
                    ['deleted_at' => true]
                ]
            ]);
    }

    public function test_can_search_users_by_name(): void
    {
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);
        User::factory()->create(['name' => 'Bob Johnson']);

        $response = $this->getJson('/api/users?search=John');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data') 
            ->assertJsonFragment(['name' => 'John Doe'])
            ->assertJsonFragment(['name' => 'Bob Johnson']);
    }

    public function test_can_search_users_by_email(): void
    {
        User::factory()->create(['email' => 'john@example.com']);
        User::factory()->create(['email' => 'jane@test.com']);

        $response = $this->getJson('/api/users?search=john@example.com');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['email' => 'john@example.com']);
    }

    public function test_can_search_in_trashed_users(): void
    {
        User::factory()->create(['name' => 'Active John']);
        User::factory()->create(['name' => 'Deleted John', 'deleted_at' => now()]);

        $response = $this->getJson('/api/users?search=John&trashed=true');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Deleted John']);
    }

    public function test_can_paginate_users(): void
    {
        User::factory()->count(20)->create();

        $response = $this->getJson('/api/users?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page'
                ]
            ])
            ->assertJson(['meta' => ['per_page' => 5]]);
    }

    public function test_returns_empty_when_no_users_found(): void
    {
        $response = $this->getJson('/api/users?search=nonexistent');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_can_show_specific_user(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'deleted_at', 'created_at', 'updated_at']
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }

    public function test_cannot_show_nonexistent_user(): void
    {
        $response = $this->getJson('/api/users/999');

        $response->assertStatus(404);
    }

    public function test_can_create_user_with_valid_data(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'deleted_at', 'created_at', 'updated_at']
            ])
            ->assertJson([
                'data' => [
                    'name' => 'New User',
                    'email' => 'newuser@example.com',
                    'deleted_at' => null
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com'
        ]);
    }

    public function test_cannot_create_user_with_invalid_email(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'invalid-email',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_create_user_with_duplicate_email(): void
    {
        $existingUser = User::factory()->create();

        $userData = [
            'name' => 'New User',
            'email' => $existingUser->email,
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_password_is_hashed_when_creating_user(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123'
        ];

        $this->postJson('/api/users', $userData);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_can_update_user_with_valid_data(): void
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    public function test_can_update_user_password(): void
    {
        $user = User::factory()->create();
        $updateData = ['password' => 'newpassword123'];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_cannot_update_nonexistent_user(): void
    {
        $updateData = ['name' => 'Updated Name'];

        $response = $this->putJson('/api/users/999', $updateData);

        $response->assertStatus(404);
    }

    public function test_cannot_update_with_duplicate_email(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $updateData = ['email' => $user1->email];

        $response = $this->putJson("/api/users/{$user2->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_soft_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Usuário deletado com sucesso.']);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_cannot_delete_nonexistent_user(): void
    {
        $response = $this->deleteJson('/api/users/999');

        $response->assertStatus(404);
    }

    public function test_deleted_user_not_in_default_listing(): void
    {
        $user = User::factory()->create();
        $this->deleteJson("/api/users/{$user->id}");

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonMissing(['id' => $user->id]);
    }

    public function test_deleted_user_appears_in_trashed_listing(): void
    {
        $user = User::factory()->create();
        $this->deleteJson("/api/users/{$user->id}");

        $response = $this->getJson('/api/users?trashed=true');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_can_restore_soft_deleted_user(): void
    {
        $user = User::factory()->create(['deleted_at' => now()]);

        $response = $this->postJson("/api/users/{$user->id}/restore");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'deleted_at', 'created_at', 'updated_at']
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'deleted_at' => null
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);
    }

    public function test_cannot_restore_nonexistent_user(): void
    {
        $response = $this->postJson('/api/users/999/restore');

        $response->assertStatus(404);
    }

    public function test_restored_user_appears_in_active_listing(): void
    {
        $user = User::factory()->create(['deleted_at' => now()]);
        $this->postJson("/api/users/{$user->id}/restore");

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_restored_user_has_null_deleted_at(): void
    {
        $user = User::factory()->create(['deleted_at' => now()]);

        $response = $this->postJson("/api/users/{$user->id}/restore");

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['deleted_at' => null]
            ]);
    }

    public function test_validates_per_page_parameter(): void
    {
        $response = $this->getJson('/api/users?per_page=101');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }

    public function test_validates_trashed_parameter(): void
    {
        $response = $this->getJson('/api/users?trashed=invalid');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['trashed']);
    }

    public function test_accepts_valid_trashed_values(): void
    {
        $validValues = ['true', '1'];

        foreach ($validValues as $value) {
            $response = $this->getJson("/api/users?trashed={$value}");
            $response->assertStatus(200);
        }
    }

    public function test_handles_empty_search_parameter(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }

    public function test_search_is_case_insensitive(): void
    {
        User::factory()->create(['name' => 'John Doe']);

        $response = $this->getJson('/api/users?search=JOHN');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'John Doe']);
    }

    public function test_pagination_with_search(): void
    {
        User::factory()->count(10)->create(['name' => 'Test User']);

        $response = $this->getJson('/api/users?search=Test&per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJson(['meta' => ['per_page' => 5]]);
    }

}
