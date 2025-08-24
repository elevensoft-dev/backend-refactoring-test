<?php

namespace Tests\Unit\Services;

use App\Core\Repositories\IUserRepository;
use App\Domain\Services\User\UserUpdateService;
use App\Exceptions\InvalidUserDataException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUpdateFailedException;
use App\Http\Request\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserUpdateServiceTest extends TestCase
{
    private $userRepositoryMock;
    private UserUpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = Mockery::mock(IUserRepository::class);
        $this->service = new UserUpdateService($this->userRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function updateUser_throws_InvalidUserDataException_when_request_empty(): void
    {
        $request = Mockery::mock(UserUpdateRequest::class);
        $request->shouldReceive('all')->once()->andReturn([]);

        $this->expectException(InvalidUserDataException::class);

        $this->service->updateUser(1, $request);
    }
    #[Test]
    public function updateUser_throws_UserNotFoundException_when_user_missing(): void
    {
        $request = Mockery::mock(UserUpdateRequest::class);
        $request->shouldReceive('all')->once()->andReturn(['name' => 'X']);
        $request->shouldReceive('filled')->with('name')->andReturn(true);
        $request->shouldReceive('filled')->with('password')->andReturn(false);
        $request->name = 'X';
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(999)
            ->andReturn(null);
        $this->expectException(UserNotFoundException::class);
        $this->service->updateUser(999, $request);
    }
    #[Test]
    public function updateUser_updates_name_and_returns_UserResource(): void
    {
        $request = Mockery::mock(UserUpdateRequest::class);
        $request->shouldReceive('all')->once()->andReturn(['name' => 'NewName']);
        $request->shouldReceive('filled')->with('name')->andReturn(true);
        $request->shouldReceive('filled')->with('password')->andReturn(false);
        $request->name = 'NewName';
        $existing = new User(['name' => 'OldName', 'email' => 'a@b.com']);
        $existing->id = 1;
        $updated = new User(['name' => 'NewName', 'email' => 'a@b.com']);
        $updated->id = 1;
        $updated->created_at = now();
        $updated->updated_at = now();
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(1)
            ->andReturn($existing);
        $this->userRepositoryMock
            ->shouldReceive('updateUser')
            ->once()
            ->with(Mockery::on(function ($user) {
                return $user instanceof User
                    && $user->name === 'NewName'
                    && (! isset($user->password) || $user->password === null);
            }), 1)
            ->andReturn(true);
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(1)
            ->andReturn($updated);
        $result = $this->service->updateUser(1, $request);
        $this->assertInstanceOf(UserResource::class, $result);
        $arr = $result->toArray(request: null);
        $this->assertEquals('NewName', $arr['name']);
        $this->assertEquals(1, $arr['id']);
    }
    #[Test]
    public function updateUser_hashes_password_and_returns_UserResource(): void
    {
        $plain = 'mysecret';
        $request = Mockery::mock(UserUpdateRequest::class);
        $request->shouldReceive('all')->once()->andReturn(['password' => $plain]);
        $request->shouldReceive('filled')->with('name')->andReturn(false);
        $request->shouldReceive('filled')->with('password')->andReturn(true);
        $request->password = $plain;
        $existing = new User(['name' => 'User', 'email' => 'u@e.com']);
        $existing->id = 2;
        $updated = new User(['name' => 'User', 'email' => 'u@e.com']);
        $updated->id = 2;
        $updated->created_at = now();
        $updated->updated_at = now();
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(2)
            ->andReturn($existing);
        $this->userRepositoryMock
            ->shouldReceive('updateUser')
            ->once()
            ->with(Mockery::on(function ($user) use ($plain) {
                if (! $user instanceof User) {
                    return false;
                }
                return isset($user->password) && password_verify($plain, $user->password);
            }), 2)
            ->andReturn(true);
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(2)
            ->andReturn($updated);
        $result = $this->service->updateUser(2, $request);
        $this->assertInstanceOf(UserResource::class, $result);
        $arr = $result->toArray(request: null);
        $this->assertEquals(2, $arr['id']);
    }
    #[Test]
    public function updateUser_throws_UserUpdateFailedException_when_update_returns_false(): void
    {
        $request = Mockery::mock(UserUpdateRequest::class);
        $request->shouldReceive('all')->once()->andReturn(['name' => 'X']);
        $request->shouldReceive('filled')->with('name')->andReturn(true);
        $request->shouldReceive('filled')->with('password')->andReturn(false);
        $request->name = 'X';
        $existing = new User();
        $existing->id = 3;
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(3)
            ->andReturn($existing);
        $this->userRepositoryMock
            ->shouldReceive('updateUser')
            ->once()
            ->with(Mockery::type(User::class), 3)
            ->andReturn(false);
        $this->expectException(UserUpdateFailedException::class);
        $this->service->updateUser(3, $request);
    }
}
