<?php

namespace Tests\Feature\Auth\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use ModelsFactoryTrait;
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_can_login_with_valid_credentials(): void
    {
        $email = $this->faker->safeEmail();
        $password = $this->faker->password();
        $encryptedPassword = Hash::make($password);

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        $userData = [
            'email'=> $email,
            'password'=> $encryptedPassword,
        ];

        $user = $this->createModel(User::class, $userData);

        $response = $this->postJson(route('auth.login'), $payload);

        $response->assertOk();

        $this->assertEquals($email, $user->email);

        $response->assertJsonStructure([
            'data' => [
                'token_type',
                'access_token',
            ],
            'message',
        ]);

        $this->assertEquals('User logged in successfully', $response->json('message'));
    }

    /**
     * @test
     */
    public function it_fails_to_login_with_invalid_credentials()
    {
        $email = $this->faker->safeEmail();
        $password = $this->faker->password();
        $encryptedPassword = Hash::make($password);

        $invalidCredentials = [
            'email' => $email,
            'password' => 'wrong-password', // Senha incorreta
        ];

        $userData = [
            'email'=> $email,
            'password'=> $encryptedPassword,
        ];

        $this->createModel(User::class, $userData);

        $response = $this->postJson(route('auth.login'), $invalidCredentials);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJsonStructure([
            'success',
            'code',
            'error' => [
                'message'
            ],
        ]);

        $this->assertFalse($response->json('success'));
    }
}
