<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{
    use DatabaseMigrations, CreatesPassportClients;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function deleteUser_without_being_authenticated_returnsForbidden(): void
    {
        // Act
        $response = $this->delete('/api/users/delete/3');
        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Não autenticado.',
        ]);
    }
    #[Test]
    public function deleteUser_with_being_authenticated_returns204(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        User::factory(10)->create();
        // Act
        $response = $this->delete('/api/users/delete/3');
        // Assert
        $response->assertStatus(204);
    }
    #[Test]
    public function deleteUser_with_being_authenticated_onNonExistingUser_returns404(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        User::factory(10)->create();
        // Act
        $response = $this->delete('/api/users/delete/30');
        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Usuário não encontrado',
        ]);
    }
}
