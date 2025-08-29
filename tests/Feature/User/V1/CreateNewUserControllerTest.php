<?php

namespace Tests\Feature\User\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class CreateNewUserControllerTest extends TestCase
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
    public function it_can_create_a_new_user()
    {
        $password = Str::random();

        $email = $this->faker->safeEmail();

        $userData = [
            'name' => $this->faker->name(),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('user.store'), $userData);

        $response->assertCreated();

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

        $this->assertEquals('User created successfully', $response->json('message'));

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    /**
     * @test
     */
    public function it_fails_to_create_user_with_invalid_data(): void
    {
        $password = Str::random();

        $userData = [
            'name' => $this->faker->name(),
            'email' => 'wrong_email.com',
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->postJson(route('user.store'), $userData);

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['email']);
    }
}
