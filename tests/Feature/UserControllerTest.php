<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_retorna_todos_os_usuarios()
    {
        $user1 = new User();
        $user1->id = 1;
        $user1->name = 'Mock User 1';
        $user1->email = 'mock1@example.com';

        $user2 = new User();
        $user2->id = 2;
        $user2->name = 'Mock User 2';
        $user2->email = 'mock2@example.com';

        $users = new Collection([$user1, $user2]);

        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('getAllUsers')
            ->once()
            ->andReturn($users);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->getJson('/api/users');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Mock User 1'])
                 ->assertJsonFragment(['name' => 'Mock User 2']);
    }

    public function test_show_retorna_usuario()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Mock User';
        $user->email = 'mock@example.com';

        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('getUserById')
            ->with(1)
            ->once()
            ->andReturn($user);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->getJson('/api/users/1');
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => 1, 'name' => 'Mock User']);
    }

    public function test_show_retorna_404_se_nao_encontrado()
    {
        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('getUserById')->with(999)->once()->andReturn(null);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->getJson('/api/users/999');
        $response->assertStatus(404);
    }

    public function test_store_cria_usuario()
    {
        $data = ['name' => 'User', 'email' => 'user@example.com', 'password' => 'password'];

        $user = new User();
        $user->id = 1;
        $user->name = 'User';
        $user->email = 'user@example.com';

        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('createUser')->with($data)->once()
            ->andReturn($user);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'user@example.com']);
    }

    public function test_update_atualiza_usuario()
    {
        $data = ['name' => 'New Name'];

        $user = new User();
        $user->id = 1;
        $user->name = 'New Name';
        $user->email = 'user@example.com';

        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('updateUser')->with(1, $data)->once()
            ->andReturn($user);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->putJson('/api/users/1', $data);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New Name']);
    }

    public function test_update_retorna_404_se_nao_encontrado()
    {
        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('updateUser')->with(999, \Mockery::any())->once()->andReturn(null);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->putJson('/api/users/999', ['name' => 'New']);
        $response->assertStatus(404);
    }

    public function test_destroy_deleta_usuario()
    {
        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('deleteUser')->with(1)->once()->andReturn(true);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->deleteJson('/api/users/1');
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'User deleted']);
    }

    public function test_destroy_retorna_404_se_nao_encontrado()
    {
        $mock = Mockery::mock(UserServiceInterface::class);
        $mock->shouldReceive('deleteUser')->with(999)->once()->andReturn(false);

        $this->app->instance(UserServiceInterface::class, $mock);

        $response = $this->deleteJson('/api/users/999');
        $response->assertStatus(404);
    }
}
