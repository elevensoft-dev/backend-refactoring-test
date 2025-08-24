<?php

namespace Tests\Unit\Services;

use App\Core\Repositories\IUserRepository;
use App\Domain\Services\User\UserListingService;
use App\Exceptions\UserNotFoundException;
use App\Http\Request\UserListingRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserListingServiceTest extends TestCase
{
    use WithFaker;

    private UserListingService $service;
    private $userRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = Mockery::mock(IUserRepository::class);
        $this->service = new UserListingService(
            $this->userRepositoryMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function paginateUsers_returns_anonymous_resource_collection(): void
    {
        // Arrange
        $request = Mockery::mock(UserListingRequest::class);
        $request->perPage = 15;
        $request->shouldReceive('input')->with('page', 1)->andReturn(1);
        $now = Carbon::now();
        $item = (object)[
            'id' => 1,
            'name' => 'Testador',
            'email' => 'teste@teste.com',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $collection = new Collection([$item]);
        $paginator = new Paginator($collection, 1, 15, 1, ['path' => '/', 'pageName' => 'page']);
        $this->userRepositoryMock
            ->shouldReceive('paginateUsers')
            ->once()
            ->with($request)
            ->andReturn($paginator);
        // Act
        $result = $this->service->paginateUsers($request);
        // Assert
        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
        $responseArray = $result->response()->getData(true);
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertCount(1, $responseArray['data']);
        $first = $responseArray['data'][0];
        $this->assertEquals(1, $first['id']);
        $this->assertEquals('Testador', $first['name']);
        $this->assertEquals('teste@teste.com', $first['email']);
        $this->assertEquals($now->toDateTimeString(), $first['createdAt']);
        $this->assertEquals($now->toDateTimeString(), $first['updatedAt']);
    }
    #[Test]
    public function getUserById_returns_user_resource_when_found(): void
    {
        // Arrange
        $now = Carbon::now();
        $user = User::factory()->make([
            'id' => 2,
            'name' => 'Fulano',
            'email' => 'fulano@ex.com',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->userRepositoryMock
            ->shouldReceive('findUserById')
            ->once()
            ->with(2)
            ->andReturn($user);
        // Act
        $result = $this->service->getUserById(2);
        // Assert
        $this->assertInstanceOf(UserResource::class, $result);
        $array = $result->toArray(request: null);
        $this->assertEquals(2, $array['id']);
        $this->assertEquals('Fulano', $array['name']);
        $this->assertEquals('fulano@ex.com', $array['email']);
        $this->assertEquals($now->toDateTimeString(), $array['createdAt']);
        $this->assertEquals($now->toDateTimeString(), $array['updatedAt']);
    }
    #[Test]
    public function getUserById_throws_UserNotFoundException_when_not_found(): void
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
        $this->service->getUserById(999);
    }
}
