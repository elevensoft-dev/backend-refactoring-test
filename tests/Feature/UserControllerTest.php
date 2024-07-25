<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $controller = App::make(UserController::class);

        $response = $controller->index();
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
    }

    public function test_show(): void
    {
        $user = User::factory()->create();
        $controller = App::make(UserController::class);
        $response = $controller->show($user->id);
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
    }

    public function test_store(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ];
        $response = $this->post('/api/users', $data);
        $this->assertEquals(201, $response->status());
        $this->assertJson($response->getContent());
    }

    public function test_update(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $response = $this->put('/api/users/' . $user->id, $data);

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
    }

    public function test_destroy(): void
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->id);

        $this->assertEquals(200, $response->status());
        $this->assertJson($response->getContent());
    }
}
