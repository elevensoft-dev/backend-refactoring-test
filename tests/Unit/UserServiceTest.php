<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use App\Services\UserFilterService;
use App\Filters\TrashedUsersFilter;
use App\Filters\SearchUsersFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $filters = [
            new TrashedUsersFilter(),
            new SearchUsersFilter(new User())
        ];
        
        $filterService = new UserFilterService($filters);
        $this->userService = new UserService(new User(), $filterService);
    }

    public function test_get_all_users_returns_paginated_active_users_by_default(): void
    {
        User::factory()->count(3)->create();
        User::factory()->create(['deleted_at' => now()]);

        $result = $this->userService->getAllUsers([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->total());
    }

    public function test_get_all_users_includes_trashed_when_requested(): void
    {
        User::factory()->count(2)->create();
        User::factory()->create(['deleted_at' => now()]);

        $result = $this->userService->getAllUsers(['trashed' => true]);

        $this->assertEquals(1, $result->total());
    }

    public function test_get_active_users_excludes_soft_deleted(): void
    {
        User::factory()->count(2)->create();
        User::factory()->create(['deleted_at' => now()]);

        $result = $this->userService->getAllUsers([]);

        $this->assertEquals(2, $result->total());
    }

    public function test_get_trashed_users_returns_only_soft_deleted(): void
    {
        User::factory()->count(2)->create();
        User::factory()->count(3)->create(['deleted_at' => now()]);

        $result = $this->userService->getAllUsers(['trashed' => true]);

        $this->assertEquals(3, $result->total());
    }

    public function test_get_user_returns_existing_user(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $result = $this->userService->getUser($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Test User', $result->name);
    }

    public function test_get_user_returns_null_for_nonexistent_user(): void
    {
        $this->expectException(ModelNotFoundException::class);
        
        $this->userService->getUser(999);
    }

    public function test_create_user_with_valid_data(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $result = $this->userService->createUser($userData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertTrue(Hash::check('password123', $result->password));
    }

    public function test_create_user_hashes_password(): void
    {
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'plaintext'
        ];

        $result = $this->userService->createUser($userData);

        $this->assertNotEquals('plaintext', $result->password);
        $this->assertTrue(Hash::check('plaintext', $result->password));
    }

    public function test_update_user_with_valid_data(): void
    {
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $updatedUser = $this->userService->updateUser($updateData, $user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals('Updated Name', $updatedUser->name);
        $this->assertEquals('updated@example.com', $updatedUser->email);
    }

    public function test_update_user_hashes_new_password(): void
    {
        $user = User::factory()->create();
        $updateData = ['password' => 'newpassword'];

        $result = $this->userService->updateUser($updateData, $user->id);

        $this->assertTrue(Hash::check('newpassword', $result->password));
    }

    public function test_update_user_throws_exception_for_nonexistent_user(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->userService->updateUser([
            'name' => 'New Name'
        ], 999);
    }

    public function test_delete_user_soft_deletes_existing_user(): void
    {
        $user = User::factory()->create();

        $this->userService->deleteUser($user->id);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_delete_user_returns_false_for_nonexistent_user(): void
    {
        $this->expectException(ModelNotFoundException::class);
        
        $this->userService->deleteUser(999);
    }

    public function test_restore_user_restores_soft_deleted_user(): void
    {
        $user = User::factory()->create(['deleted_at' => now()]);

        $result = $this->userService->restoreUser($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertNull($result->deleted_at);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);
    }

    public function test_restore_user_throws_exception_for_nonexistent_user(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->userService->restoreUser(999);
    }
}
