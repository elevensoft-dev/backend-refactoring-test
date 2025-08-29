<?php

namespace Tests\Feature\User\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class GetAllUsersControllerTest extends TestCase
{
    use RefreshDatabase;
    Use ModelsFactoryTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_get_all_users()
    {
        $users = $this->createManyModels(User::class, 5);

        $user = $users->first();

        Passport::actingAs($user);

        $response = $this->getJson(route('users.all'));

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
        ]);

        $this->assertEquals(
            'Users retrieved successfully.',
            $response->json('message')
        );

        $this->assertCount(5, $response->json('data'));
    }

    /**
     * @test
     */
    public function it_fails_to_get_users_if_not_authenticated()
    {
        $response = $this->getJson(route('users.all'));

        $response->assertUnauthorized();

        $this->assertEquals('Unauthenticated.', $response->json('error.message'));
    }
}
