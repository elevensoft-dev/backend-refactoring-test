<?php

namespace Tests\Unit\Services;

use App\Core\Repositories\IUserRepository;
use App\Domain\Services\User\UserCreateService;
use App\Http\Request\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserCreateServiceTest extends TestCase
{
    private $userRepositoryMock;
    private UserCreateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = Mockery::mock(IUserRepository::class);
        $this->service = new UserCreateService($this->userRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function createUser_calls_repository_with_hashed_password_and_returns_UserResource(): void
    {
        // Arrange
        $name = 'Test User';
        $email = 'test@example.com';
        $plainPassword = 'supersecret';
        $request = Mockery::mock(UserCreateRequest::class);
        $request->name = $name;
        $request->email = $email;
        $request->password = $plainPassword;
        $now = Carbon::now();
        $createdUser = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($plainPassword),
        ]);
        $createdUser->id = 123;
        $createdUser->created_at = $now;
        $createdUser->updated_at = $now;
        $this->userRepositoryMock
            ->shouldReceive('createUser')
            ->once()
            ->with(Mockery::on(function ($user) use ($name, $email, $plainPassword) {
                if (! $user instanceof User) {
                    return false;
                }
                if ($user->name !== $name || $user->email !== $email) {
                    return false;
                }
                return password_verify($plainPassword, $user->password);
            }))
            ->andReturn($createdUser);
        // Act
        $result = $this->service->createUser($request);
        // Assert
        $this->assertInstanceOf(UserResource::class, $result);
        $array = $result->toArray(request: null);
        $this->assertEquals(123, $array['id']);
        $this->assertEquals($name, $array['name']);
        $this->assertEquals($email, $array['email']);
        $this->assertEquals($now->toDateTimeString(), $array['createdAt']);
        $this->assertEquals($now->toDateTimeString(), $array['updatedAt']);
    }
}
