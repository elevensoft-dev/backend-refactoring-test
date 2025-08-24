<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class UserListingTest extends TestCase
{
    use DatabaseMigrations, CreatesPassportClients;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function listingUser_without_being_authenticated_returnsForbidden(): void
    {
        // Arrange
        User::factory(10)->create();
        $data = [
            'page' => 1,
            'perPage' => 10,
        ];
        // Act
        $response = $this->postJson('/api/users/list', $data);
        // $responseBody = json_decode($response->getContent(), true);
        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Não autenticado.',
        ]);
    }
    #[Test]
    public function listingUser_with_being_authenticated_returnsOk(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        User::factory(10)->create();
        $data = [
            'page' => 1,
            'perPage' => 10,
        ];
        // Act
        $response = $this->postJson('/api/users/list', $data);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'createdAt',
                'updatedAt',
            ],
        ]);
    }
    #[Test]
    public function listingUser_with_being_authenticated_withFilterName_returnsOk(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        User::factory(100)->create();
        $data = [
            'page' => 1,
            'perPage' => 10,
            'name' => substr($user->name, 0, 3),
        ];
        // Act
        $response = $this->postJson('/api/users/list', $data);
        $responseBody = json_decode($response->getContent(), true);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'createdAt',
                'updatedAt',
            ],
        ]);
        foreach ($responseBody as $user) {
            $this->assertStringContainsString($data['name'], $user['name']);
        }
    }
    #[Test]
    public function listingUser_with_being_authenticated_withFilterEmail_returnsOk(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        User::factory(100)->create();
        $data = [
            'page' => 1,
            'perPage' => 10,
            'email' => substr($user->email, 0, 3),
        ];
        // Act
        $response = $this->postJson('/api/users/list', $data);
        $responseBody = json_decode($response->getContent(), true);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'createdAt',
                'updatedAt',
            ],
        ]);
        foreach ($responseBody as $user) {
            $this->assertStringContainsString($data['email'], $user['email']);
        }
    }
    #[Test]
    public function findUserById_with_being_authenticated_returnsOk(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        // Act
        $response = $this->get('/api/users/' . $user->id);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'createdAt',
            'updatedAt',
        ]);
    }
    #[Test]
    public function findUserById_without_being_authenticated_returnsForbidden(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        // Act
        $response = $this->get('/api/users/' . $user->id);
        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Não autenticado.',
        ]);
    }
}
