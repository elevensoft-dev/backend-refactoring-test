<?php

namespace Tests\Unit\Auth\V1;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\LogoutErrorException;
use App\Http\Resources\Auth\V1\LoginResource;
use App\Http\Resources\Auth\V1\LogoutResource;
use App\Models\User;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Passport\Token;
use Mockery;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use WithFaker;

    use ModelsFactoryTrait;

    private AuthServiceInterface $authService;

    private $authMock;

    private $userMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->userMock = Mockery::mock(User::class);

        $this->authService = $this->app->make(AuthServiceInterface::class);

        Carbon::setTestNow(Carbon::parse(now()->toDateTimeString()));
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_bearer_token_with_right_credentials(): void
    {
        $credentials = [
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
        ];

        $fakeAccessToken = Str::random(30);

        $this->userMock
            ->shouldReceive('createToken')
            ->once()
            ->with('auth_token')
            ->andReturn((object) ['accessToken' => $fakeAccessToken]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($this->userMock);

        $result = $this->authService->getAccessToken($credentials);

        $this->assertInstanceOf(LoginResource::class, $result);

        $this->assertEquals('Bearer', $result->resource['token_type']);

        $this->assertEquals($fakeAccessToken, $result->resource['access_token']);
    }

    /** @test */
    public function it_throws_exception_if_credentials_are_wrong()
    {
        $credentials = [
            'email' => $this->faker->safeEmail(),
            'password' => 'wrong_pass',
        ];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $this->expectException(InvalidCredentialsException::class);

        $this->authService->getAccessToken($credentials);
    }

    /**
     * @test
     */
    public function it_returns_true_when_token_revoked_successfully(): void
    {
        $requestMock = Mockery::mock(Request::class);

        $tokenMock = Mockery::mock(Token::class);

        $requestMock->shouldReceive('user')->andReturn($this->userMock);

        $this->userMock->shouldReceive('token')->andReturn($tokenMock);

        $tokenMock->shouldReceive('revoke')->andReturn(true);

        $response = $this->authService->revokeToken($requestMock);

        $this->assertInstanceOf(LogoutResource::class, $response);

        $this->assertTrue($response->resource['token_revoked']);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_token_is_not_revoked()
    {
        $requestMock = Mockery::mock(Request::class);

        $tokenMock = Mockery::mock(Token::class);

        $requestMock->shouldReceive('user')->andReturn($this->userMock);

        $this->userMock->shouldReceive('token')->andReturn($tokenMock);

        $tokenMock->shouldReceive('revoke')->andReturn(false);

        $this->expectException(LogoutErrorException::class);

        $this->authService->revokeToken($requestMock);
    }
}
