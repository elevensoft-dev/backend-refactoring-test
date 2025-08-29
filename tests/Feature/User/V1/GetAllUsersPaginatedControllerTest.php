<?php

namespace Tests\Feature\User\V1;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class GetAllUsersPaginatedControllerTest extends TestCase
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

    /**
     * @test
     */
    public function it_can_get_a_list_of_paginated_users(): void
    {
        $usersCollection = $this->createManyModels(User::class, 8);

        Passport::actingAs($usersCollection->first());

        $response = $this->getJson(route('users.all.paginate'));

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            'pagination' => [
                'data',
                'meta',
                'links',
            ],
        ]);

        $this->assertEquals(
            'Users retrieved successfully.',
            $response->json('message')
        );

        $this->assertCount(8, $response->json('pagination.data'));
    }

    /**
     * @test
     */
    public function it_fails_to_get_users_paginated_if_not_authenticated()
    {
        $response = $this->getJson(route('users.all.paginate'));

        $response->assertUnauthorized();

        $this->assertEquals('Unauthenticated.', $response->json('error.message'));
    }
}
