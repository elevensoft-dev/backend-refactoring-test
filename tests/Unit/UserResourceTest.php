<?php

namespace Tests\Unit;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_transforms_user_data_correctly(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null
        ]);

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertEquals($user->id, $array['id']);
        $this->assertEquals('John Doe', $array['name']);
        $this->assertEquals('john@example.com', $array['email']);
        $this->assertArrayHasKey('email_verified_at', $array);
        $this->assertArrayHasKey('created_at', $array);
        $this->assertArrayHasKey('updated_at', $array);
        $this->assertArrayHasKey('deleted_at', $array);
    }

    public function test_includes_deleted_at_field(): void
    {
        $user = User::factory()->create(['deleted_at' => now()]);

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertArrayHasKey('deleted_at', $array);
        $this->assertNotNull($array['deleted_at']);
    }

    public function test_deleted_at_is_null_for_active_users(): void
    {
        $user = User::factory()->create(['deleted_at' => null]);

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertArrayHasKey('deleted_at', $array);
        $this->assertNull($array['deleted_at']);
    }

    public function test_does_not_include_password_field(): void
    {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertArrayNotHasKey('password', $array);
    }

    public function test_does_not_include_remember_token(): void
    {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_formats_timestamps_correctly(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertNotNull($array['created_at']);
        $this->assertNotNull($array['updated_at']);
        $this->assertNotNull($array['email_verified_at']);
    }

    public function test_handles_null_email_verified_at(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $this->assertArrayHasKey('email_verified_at', $array);
        $this->assertNull($array['email_verified_at']);
    }

    public function test_resource_structure_is_consistent(): void
    {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $array = $resource->toArray(new Request());

        $expectedKeys = [
            'id', 'name', 'email', 'email_verified_at', 
            'created_at', 'updated_at', 'deleted_at'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array, "Missing key: {$key}");
        }
    }

    public function test_collection_transforms_multiple_users(): void
    {
        $users = User::factory()->count(3)->create();

        $collection = UserResource::collection($users);
        $array = $collection->toArray(new Request());

        $this->assertCount(3, $array);
        
        foreach ($array as $userArray) {
            $this->assertArrayHasKey('id', $userArray);
            $this->assertArrayHasKey('name', $userArray);
            $this->assertArrayHasKey('email', $userArray);
        }
    }
}
