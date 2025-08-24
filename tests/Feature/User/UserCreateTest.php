<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use DatabaseMigrations, CreatesPassportClients, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function createUser_without_being_authenticated_returnsForbidden(): void
    {
        // Arrange
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Não autenticado.',
        ]);
    }
    #[Test]
    public function createUser_with_being_authenticated_withValidData_returnsCreated(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'createdAt',
            'updatedAt',
        ]);
    }
    #[Test]
    public function createUser_with_being_authenticated_withInvalidEmail_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => 'invalid-email',
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Erro de validação',
            'errors' => [
                'email' => [
                    'O campo email deve ser um email válido',
                ],
            ],
        ]);
    }
    #[Test]
    public function createUser_with_being_authenticated_withInvalidPassword_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'inval',
            'passwordConfirmation' => 'inval',
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Erro de validação',
            'errors' => [
                'password' => [
                    'O campo senha deve ter no mínimo 6 caracteres'
                ],
            ],
        ]);
    }
    #[Test]
    public function createUser_with_being_authenticated_withInvalidPasswordConfirmation_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'passwordConfirmation' => 'invalid',
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
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
    #[Test]
    public function createUser_with_being_authenticated_withInvalidName_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => 'a',
            'email' => $this->faker->email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Erro de validação',
            'errors' => [
                'name' => [
                    'O campo nome deve ter no mínimo 3 caracteres'
                ],
            ],
        ]);
    }
    #[Test]
    public function createUser_with_being_authenticated_withAlreadyUsedEmail_returnsBadRequest(): void
    {
        // Arrange
        $user = User::factory()->createOne();
        $this->actingAs($user, 'api');
        $password = $this->faker->password;
        $data = [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => $password,
            'passwordConfirmation' => $password,
        ];
        // Act
        $response = $this->postJson('/api/users/create', $data);
        // Assert
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Erro de validação',
            'errors' => [
                'email' => [
                    'O campo email já está em uso'
                ]
            ],
        ]);
    }
}
