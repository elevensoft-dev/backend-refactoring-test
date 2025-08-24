<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use DatabaseMigrations, CreatesPassportClients, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function updateUser_without_being_authenticated_returnsForbidden(): void
    {
        // Arrange
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->putJson('api/users/update/2', $data);
        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Não autenticado.',
        ]);
    }
    #[Test]
    public function updateUser_with_valid_data_returnsOk(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->putJson('api/users/update/' . $user->id, $data);
        $responseBody = json_decode($response->getContent(), true);
        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'createdAt',
            'updatedAt',
        ]);
        $this->assertEquals($data['name'], $responseBody['name']);
    }
    #[Test]
    public function updateUser_onInvalidUser_returnsNotFound(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->putJson('api/users/update/2', $data);
        // Assert
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Usuário não encontrado',
        ]);
    }
    #[Test]
    public function updateUser__with_being_authenticated_withInvalidPassword_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'password' => $password,
            'passwordConfirmation' => $password . '1',
        ];
        // Act
        $response = $this->putJson('api/users/update/' . $user->id, $data);
        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Erro de validação',
            'errors' => [
                'passwordConfirmation' => [
                    'O campo confirmação de senha deve ser igual ao campo senha'
                ],
            ],
        ]);
    }
}
