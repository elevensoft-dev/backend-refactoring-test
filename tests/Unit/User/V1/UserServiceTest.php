<?php

namespace Tests\Unit\User\V1;

use App\Exceptions\CollectionEmptyException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Resources\User\V1\UserPaginationCollection;
use App\Http\Resources\User\V1\UserResource;
use App\Models\User;
use App\Repository\User\V1\Contracts\UserRepositoryInterface;
use App\Service\User\V1\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\ModelsFactoryTrait;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    use ModelsFactoryTrait;

    private $userRepositoryMock;

    private $userMock;

    private $userResourceMock;

    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);

        $this->userService = new UserService($this->userRepositoryMock);

        Carbon::setTestNow(Carbon::parse(now()->toDateTimeString()));
    }

    public function tearDown(): void
    {
        Mockery::close();

        Carbon::setTestNow();

        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_all_users_as_resource_collection(): void
    {
        $usersCollection = $this->makeManyModels(User::class, 5);

        $this->userRepositoryMock
            ->shouldReceive('all')
            ->andReturn($usersCollection);

        $actualCollectionReturn = $this->userService->getAllUsers();

        $this->assertInstanceOf(ResourceCollection::class, $actualCollectionReturn);

        $this->assertCount(5, $actualCollectionReturn->collection);
    }

    /**
     * @test
     *
     */
    public function it_throws_exception_if_no_users_found(): void
    {
        $this->userRepositoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect());

        $this->expectException(CollectionEmptyException::class);

        $this->userService->getAllUsers();
    }

    /**
     * @test
     */
    public function it_returns_all_paginated_users_as_user_pagination_collection(): void
    {
        $usersCollection = $this->makeManyModels(User::class, 5);

        $paginationCollection = new LengthAwarePaginator($usersCollection,5, 10);

        $this->userRepositoryMock
            ->shouldReceive('allPaginated')
            ->with(10)
            ->once()
            ->andReturn($paginationCollection);

        $actualCollectionPaginatedReturn = $this->userService->getAllUsersPaginated();

        $this->assertInstanceOf(
            UserPaginationCollection::class,
            $actualCollectionPaginatedReturn
        );

        $this->assertCount(5, $actualCollectionPaginatedReturn->collection);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_no_users_found_in_pagination()
    {
        $emptyPaginator = new LengthAwarePaginator([], 0, 10);

        $this->userRepositoryMock
            ->shouldReceive('allPaginated')
            ->with(10)
            ->once()
            ->andReturn($emptyPaginator);

        $this->expectException(CollectionEmptyException::class);

        $this->userService->getAllUsersPaginated();
    }

    /**
     * @test
     */
    public function it_returns_user_resource_when_user_is_found()
    {
        $user = $this->makeModel(User::class);

        $this->userRepositoryMock
            ->shouldReceive('getById')
            ->with(1)
            ->once()
            ->andReturn($user);

        $result = $this->userService->getUserById(1);

        $this->assertInstanceOf(UserResource::class, $result);
    }

    /**
     * @test
     */
    public function it_returns_exception_when_user_is_not_found()
    {
        $this->userRepositoryMock
            ->shouldReceive(methodNames: 'getById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        $this->userService->getUserById(999);
    }

    /**
     * @test
     */
    public function it_creates_a_new_user_and_returns_user_resource(): void
    {
        $data = [
            'name' => 'User test',
            'email' => 'testuser@phpunit.com',
            'password' => bcrypt('123456')
        ];

        $user = $this->makeModel(User::class, $data);

        $this->userRepositoryMock
            ->shouldReceive('create')
            ->with(Mockery::on(fn($input) => $input['email'] === $data['email']))
            ->andReturn($user);

        $result = $this->userService->storeNewUser($data);

        $this->assertInstanceOf(UserResource::class, $result);

        $this->assertEquals($data['email'], $result->email);
    }

    /**
     * @test
     */
    public function it_updates_an_existing_user_and_returns_user_resource()
    {
        $data = [
            'name' => 'User test',
            'email' => 'testuser@phpunit.com',
            'password' => bcrypt('123456')
        ];

        $user = $this->makeModel(User::class, $data);

        $updatedData = ['name' => 'User test update'];

        $this->userRepositoryMock
            ->shouldReceive('getById')
            ->with(1)
            ->andReturn($user);

        $this->userRepositoryMock
            ->shouldReceive('update')
            ->with($updatedData, $user)
            ->andReturn(tap($user)->fill($updatedData));

        $result = $this->userService->updateUser($updatedData, 1);

        $this->assertInstanceOf(UserResource::class, $result);

        $this->assertEquals('User test update', $result->name);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_updating_with_not_found_user()
    {
        $this->userRepositoryMock
            ->shouldReceive('getById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        $this->userService->updateUser(['name' => 'User test update'], 999);
    }

    /**
     * @test
     */
    public function it_deletes_a_user_successfully()
    {
        $user = User::factory()->make(['id' => 1]);

        $this->userRepositoryMock
            ->shouldReceive('getById')
            ->with(1)
            ->andReturn($user);

        $this->userRepositoryMock
            ->shouldReceive('delete')
            ->with($user)
            ->andReturn($user);

        $result = $this->userService->deleteUser(1);

        $this->assertInstanceOf(UserResource::class, $result);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_deleting_nonexistent_user()
    {
        $this->userRepositoryMock
            ->shouldReceive('getById')
            ->with(999)
            ->andReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        $this->userService->deleteUser(999);
    }
}
