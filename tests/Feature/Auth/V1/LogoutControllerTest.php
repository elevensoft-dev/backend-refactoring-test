<?php

namespace Tests\Feature\Auth\V1;

use App\Http\Resources\Auth\V1\LogoutResource;
use App\Models\User;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Mockery;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
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
    public function it_can_logout_a_logged_in_user(): void
    {
        $authServiceMock = Mockery::mock(AuthServiceInterface::class);

        $authServiceMock->shouldReceive('revokeToken')
            ->once()
            ->andReturn(new LogoutResource(['token_revoked' => true]));

        $this->app->instance(AuthServiceInterface::class, $authServiceMock);

        $user = $this->createModel(User::class);

        Passport::actingAs($user, ['*']);

        $response = $this->postJson(route('auth.logout'));

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'code',
            'message',
            'data' => [
                'logout'
            ],
        ]);

        $this->assertEquals('User logged out successfully', $response->json('message'));

        $this->assertTrue($response->json('data.logout'));
    }
}
