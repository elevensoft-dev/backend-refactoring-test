<?php

namespace Tests\Unit\Services;

use App\Core\Repositories\IUserRepository;
use App\Domain\Services\User\UserDeleteService;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserDeleteServiceTest extends TestCase
{
    private $userRepositoryMock;
    private UserDeleteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = Mockery::mock(IUserRepository::class);
        $this->service = new UserDeleteService($this->userRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function deleteUser_returnsTrueOnSuccess(): void
    {
        // Arrange
        $user = User::factory()->make();
        $user->id = 1;
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(1)
            ->andReturn($user);
        $this->userRepositoryMock
            ->shouldReceive('deleteUser')
            ->once()
            ->with(1)
            ->andReturn(true);
        // Act
        $result = $this->service->deleteUser(1);
        // Assert
        $this->assertTrue($result);
    }
    #[Test]
    public function deleteUser_returnsFalseWhenRepositoryDeleteFails(): void
    {
        // Arrange
        $user = User::factory()->make();
        $user->id = 2;
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(2)
            ->andReturn($user);
        $this->userRepositoryMock
            ->shouldReceive('deleteUser')
            ->once()
            ->with(2)
            ->andReturn(false);
        // Act
        $result = $this->service->deleteUser(2);
        // Assert
        $this->assertFalse($result);
    }
    #[Test]
    public function deleteUser_throwsUserNotFoundException_when_user_missing(): void
    {
        // Arrange
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(999)
            ->andReturn(null);
        // Assert
        $this->expectException(UserNotFoundException::class);
        // Act
        $this->service->deleteUser(999);
    }
}
