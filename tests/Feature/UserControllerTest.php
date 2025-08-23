<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $baseUrl = '/api/users';

    /** @test */
    public function it_lists_users_with_pagination()
    {
        User::factory()->count(30)->create();

        $authUser = User::factory()->create();
        $this->actingAs($authUser);

        $response = $this->getJson($this->baseUrl);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'created_at', 'updated_at']
                     ],
                     'links',
                     'meta'
                 ]);
    }

    /** @test */
    public function it_shows_a_user()
    {
        $authUser = User::factory()->create();
        $this->actingAs($authUser);

        $user = User::factory()->create();

        $response = $this->getJson("{$this->baseUrl}/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'email' => $user->email,
                 ]);
    }

    /** @test */
    public function it_creates_a_user()
    {
        $authUser = User::factory()->create();
        $this->actingAs($authUser);

        $payload = [
            'name' => 'Novo Usuário',
            'email' => 'novo@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson($this->baseUrl, $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Novo Usuário',
                     'email' => 'novo@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo@example.com'
        ]);
    }

    /** @test */
    public function it_updates_a_user()
    {
        $authUser = User::factory()->create();
        $this->actingAs($authUser);

        $user = User::factory()->create();

        $payload = [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@example.com',
        ];

        $response = $this->putJson("{$this->baseUrl}/{$user->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Nome Atualizado',
                     'email' => 'atualizado@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'atualizado@example.com',
        ]);
    }

    /** @test */
    public function it_deletes_a_user()
    {
        $authUser = User::factory()->create();
        $this->actingAs($authUser);

        $user = User::factory()->create();

        $response = $this->deleteJson("{$this->baseUrl}/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
