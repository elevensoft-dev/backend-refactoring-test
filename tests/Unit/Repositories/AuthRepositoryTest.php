<?php

namespace Tests\Unit\Repositories;

use App\Domain\Repositories\AuthRepository;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\Concerns\CreatesPassportClients;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    use CreatesPassportClients;

    private AuthRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AuthRepository();
        $this->artisan('migrate');
        $this->setUpPassportClient();
    }

    #[Test]
    public function login_withValidCredentials_returnsTokenData(): void
    {
        // Arrange
        $request = Mockery::mock('App\Http\Request\LoginAuthRequest');
        $request->shouldReceive('only')
            ->with(['email', 'password'])
            ->andReturn(['email' => 'test@test.com', 'password' => 'secret']);
        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@test.com', 'password' => 'secret'])
            ->andReturn(true);
        $user = Mockery::mock(User::class)->makePartial();
        $user->name = 'John Doe';
        $user->email = 'test@test.com';
        $token = new Token();
        $token->expires_at = now()->addHour();
        $tokenResult = new PersonalAccessTokenResult('fake_token', $token);
        $user->shouldReceive('createToken')
            ->with('Personal Access Token')
            ->andReturn($tokenResult);
        Auth::shouldReceive('user')->andReturn($user);
        // Act
        $result = $this->repository->login($request);
        // Assert
        $this->assertEquals('fake_token', $result['access_token']);
        $this->assertEquals('Bearer', $result['token_type']);
        $this->assertEquals('John Doe', $result['user']['name']);
        $this->assertEquals('test@test.com', $result['user']['email']);
    }
    #[Test]
    public function login_withInvalidCredentials_throwsException(): void
    {
        // Arrange
        $request = Mockery::mock('App\Http\Request\LoginAuthRequest');
        $request->shouldReceive('only')->andReturn([
            'email' => 'wrong@test.com',
            'password' => 'invalid',
        ]);
        Auth::shouldReceive('attempt')->andReturn(false);
        // Assert
        $this->expectException(InvalidCredentialsException::class);
        // Act
        $this->repository->login($request);
    }
    #[Test]
    public function logout_withAuthenticatedUser_revokesTokens(): void
    {
        // Arrange
        $user = Mockery::mock(User::class)->makePartial();
        $token = Mockery::mock();
        $token->shouldReceive('revoke')->once();
        $user->tokens = collect([$token]);
        Auth::shouldReceive('user')->andReturn($user);
        // Act
        $this->repository->logout();
        // Assert
        $this->assertTrue(true);
    }
    #[Test]
    public function logout_withNoAuthenticatedUser_doesNothing(): void
    {
        // Arrange
        Auth::shouldReceive('user')->andReturn(null);
        // Act
        $this->repository->logout();
        // Assert
        $this->assertTrue(true);
    }
}
