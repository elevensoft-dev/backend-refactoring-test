<?php

namespace Tests\Feature\User\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class GetUserByIdControllerTest extends TestCase
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
    public function it_can_get_a_user_by_id()
    {
        $user = $this->createModel(User::class);

        $retrievedUser = $this->createModel(User::class);

        Passport::actingAs($user);

        $response = $this->getJson(route('user.get-by-id', ['id' => $retrievedUser->id]));

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

        $this->assertEquals($retrievedUser->id, $response->json('data.id'));

        $this->assertEquals($retrievedUser->name, $response->json('data.name'));

        $this->assertEquals($retrievedUser->email, $response->json('data.email'));
    }

    /**
     * @test
     */
    public function it_fails_to_get_user_by_id_if_not_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('user.get-by-id', ['id' => $user->id]));

        $response->assertUnauthorized();

        $this->assertEquals('Unauthenticated.', $response->json('error.message'));
    }
}
