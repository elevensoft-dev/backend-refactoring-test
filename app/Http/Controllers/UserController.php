<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\UseCases\User\CreateUserUseCase;
use App\UseCases\User\DeleteUserUseCase;
use App\UseCases\User\DTOs\CreateUserDto;
use App\UseCases\User\DTOs\ListUsersDto;
use App\UseCases\User\DTOs\UpdateUserDto;
use App\UseCases\User\ListUsersUseCase;
use App\UseCases\User\ShowUserUseCase;
use App\UseCases\User\UpdateUserUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      summary="Get list of users",
     *      tags={"Users"},
     *      description="Returns paginated list of users",
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\Parameter(
     *          name="filters[name]",
     *          in="query",
     *          required=false,
     *
     *          @OA\Schema(type="string"),
     *          description="Filter by user name"
     *      ),
     *
     *      @OA\Parameter(
     *          name="filters[active]",
     *          in="query",
     *          required=false,
     *
     *          @OA\Schema(type="boolean"),
     *          description="Filter by user active status"
     *      ),
     *
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *
     *          @OA\Schema(type="integer"),
     *          description="Page number for pagination"
     *      ),
     *
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          required=false,
     *
     *          @OA\Schema(type="integer"),
     *          description="Items per page for pagination"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *              @OA\Property(property="current_page", type="integer"),
     *              @OA\Property(property="last_page", type="integer"),
     *              @OA\Property(property="per_page", type="integer"),
     *              @OA\Property(property="total", type="integer"),
     *              @OA\Property(property="from", type="integer"),
     *              @OA\Property(property="to", type="integer")
     *          )
     *      ),
     *
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request, ListUsersUseCase $listUsersUseCase): AnonymousResourceCollection
    {
        $dto = new ListUsersDto(
            filters: $request->input('filters', []),
            perPage: $request->integer('per_page', 15),
        );

        return UserResource::collection($listUsersUseCase->handle($dto));
    }

    /**
     * Show a specific user resource
     *
     * @OA\Get(
     *      path="/users/{user}",
     *      operationId="getUserById",
     *      summary="Get user by ID",
     *      tags={"Users"},
     *      description="Returns a single user resource",
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *
     *          @OA\Schema(type="integer"),
     *          description="User ID"
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function show(int $id, ShowUserUseCase $showUserUseCase): UserResource
    {
        $user = $showUserUseCase->handle($id);

        return new UserResource($user);
    }

    /**
     * Store a newly created user in storage
     *
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      summary="Create a new user",
     *      tags={"Users"},
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *
     *      @OA\Response(response=422, description="Validation error"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function store(StoreUserRequest $request, CreateUserUseCase $createUserUseCase): UserResource
    {
        $dto = new CreateUserDto(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        $user = $createUserUseCase->handle($dto);

        return new UserResource($user);
    }

    /**
     * Update the specified user in storage
     *
     * @OA\Put(
     *      path="/users/{user}",
     *      operationId="updateUser",
     *      summary="Update an existing user",
     *      tags={"Users"},
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *
     *          @OA\Schema(type="integer"),
     *          description="User ID"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="User updated successfully",
     *
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(response=422, description="Validation error"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function update(int $id, UpdateUserRequest $request, UpdateUserUseCase $updateUserUseCase): UserResource
    {
        $dto = new UpdateUserDto(
            id: $id,
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        $user = $updateUserUseCase->handle($dto);

        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage
     *
     * @OA\Delete(
     *      path="/users/{user}",
     *      operationId="deleteUser",
     *      summary="Delete a user",
     *      tags={"Users"},
     *      security={{"bearerAuth": {}}},
     *
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *
     *          @OA\Schema(type="integer"),
     *          description="User ID"
     *      ),
     *
     *      @OA\Response(
     *          response=204,
     *          description="User deleted successfully"
     *      ),
     *      @OA\Response(response=404, description="User not found"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy(int $id, DeleteUserUseCase $deleteUserUseCase): Response
    {
        $deleteUserUseCase->handle($id);

        return response()->noContent();
    }
}
