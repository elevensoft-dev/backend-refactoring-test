<?php

namespace Tests\Feature\User\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class UpdateUserControllerTest extends TestCase
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
    public function it_can_update_an_existing_user(): void
    {
        $user = $this->createModel(User::class);

        $updatedData = [
            'name' => 'Updated Name'
        ];

        Passport::actingAs($user);

        $response = $this->putJson(
            route(
                'user.update',
                ['id' => $user->id]
            ),
            $updatedData);

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

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name'
        ]);
    }

    /**
     * @test
     */
    public function it_fails_to_update_user_if_not_authenticated()
    {
        $user = $this->createModel(User::class);

        $updatedData = [
            'name' => 'Updated Name'
        ];

        $response = $this->putJson(
            route(
                'user.update',
                ['id' => $user->id]
            ),
            $updatedData);

        $response->assertUnauthorized();

        $this->assertEquals('Unauthenticated.', $response->json('error.message'));
    }

    /**
     * @test
     */
    public function it_fails_to_create_user_with_invalid_data(): void
    {
        $user = $this->createModel(User::class);

        $updatedData = [
            'name' => ''
        ];

        Passport::actingAs($user);

        $response = $this->putJson(
            route(
                'user.update',
                ['id' => $user->id]
            ),
            $updatedData);

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['name']);
    }
}
