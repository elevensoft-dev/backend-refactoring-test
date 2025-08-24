<?php

namespace Tests\Unit\Repositories;

use App\Domain\Repositories\UserRepository;
use App\Http\Request\UserListingRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    #[Test]
    public function paginateUsers_returnsPaginator(): void
    {
        // Arrange
        User::factory(100)->create();
        $request = Mockery::mock(UserListingRequest::class);
        $request->perPage = 2;
        $request->name = null;
        $request->email = null;
        $request->shouldReceive('input')->with('page', 1)->andReturn(1);
        // Act
        $paginator = $this->repository->paginateUsers($request);
        // Asset
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertEquals(2, $paginator->perPage());
        $this->assertEquals(100, $paginator->total());
        $this->assertCount(2, $paginator->items());
    }
    #[Test]
    public function findUserById_returnsUser(): void
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $result = $this->repository->findUserById($user->id);
        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
        $this->assertEquals($user->name, $result->name);
        $this->assertEquals($user->email, $result->email);
    }
    #[Test]
    public function createUser_savesAndReturnsUser(): void
    {
        // Arrange
        $user = User::factory()->makeOne();
        // Act
        $result = $this->repository->createUser($user);
        // Assert
        $this->assertSame($user, $result);
    }
    #[Test]
    public function updateUser_returnsTrueOnSuccess(): void
    {
        // Arrange
        $user = User::factory()->createOne(['name' => 'OldName']);
        $userForUpdate = new User();
        $userForUpdate->name = 'NewName';
        // Act
        $result = $this->repository->updateUser($userForUpdate, $user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'NewName']);
    }
    #[Test]
    public function deleteUser_returnsTrueOnSuccess(): void
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $result = $this->repository->deleteUser($user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
