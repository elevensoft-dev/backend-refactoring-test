<?php

namespace Tests\Feature\User\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class DeleteUserControllerTest extends TestCase
{
    Use ModelsFactoryTrait;
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_can_delete_an_existing_user(): void
    {
        $user = $this->createModel(User::class);

        $userToDelete = $this->createModel(User::class);

        Passport::actingAs($user);

        $response = $this->deleteJson( route('user.delete', ['id' => $userToDelete->id]));

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id
        ]);
    }

    /**
     * @test
     */
    public function it_fails_to_delete_user_if_not_authenticated(): void
    {
        $user = $this->createModel(User::class);

        $response = $this->deleteJson(route('user.delete', ['id' => $user->id]));

        $response->assertUnauthorized();

        $this->assertEquals('Unauthenticated.', $response->json('error.message'));
    }
}
