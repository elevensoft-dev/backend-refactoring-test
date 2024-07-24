<?php 
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }
    
    public function it_can_create_a_user()
    {
        $this->authenticatedUser();
        $userData = User::factory()->make()->toArray();
        $userData['password'] = '123456a';

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                 ]);
    
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(\Hash::check('123456a', $user->password));
    }

    public function it_can_update_a_user() 
    {
        $this->authenticatedUser();
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Test name',
            'email' => 'test@hotmail.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                    'name' => 'Test name',
                    'email' => 'test@hotmail.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test name',
            'email' => 'test@hotmail.com',
        ]);
    }

    public function it_can_list_all_users()
    {
        $this->authenticatedUser();
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function it_can_view_a_single_user()
    {
        $this->authenticatedUser();
        $user = User::factory()->create();

        $response = $this->getJson("/api/users{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                 ]);
    }

    public function it_can_delete_a_user()
    {
        $this->authenticatedUser();
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function it_cannot_list_all_users_when_unauthenticated()
    {
        User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(401);

    }

    public function it_cannot_view_a_single_user_when_unauthenticated()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(401);
    }

    public function it_cannot_create_a_user_when_unauthenticated()
    {
        $userData = User::factory()->make()->toArray();

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(401);
    }

    public function it_cannot_update_a_user_when_unauthenticated()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Test Name',
            'email' => 'test@hotmail.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(401);
    }

    public function it_cannot_delete_a_user_when_unauthenticated()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(401);
    }

    public function it_cannot_update_a_user_when_forbidden()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api'); 
        $anotherUser = User::factory()->create(); 

        $updateData = [
            'name' => 'Test Name 2',
            'email' => 'test2@hotmail.com',
        ];

        $response = $this->putJson("/api/users/{$anotherUser->id}", $updateData);

        $response->assertStatus(403);
    }

    public function it_cannot_delete_a_user_when_forbidden()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api'); 
        $anotherUser = User::factory()->create(); 

        $response = $this->deleteJson("/api/users/{$anotherUser->id}");

        $response->assertStatus(403);
    }


}