<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\IndexUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      summary="Get list of users with search, pagination and soft delete control",
     *      tags={"Users"},
     *      description="Returns paginated list of users with optional search. Use trashed=true to get only deleted users",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Search users by name or email",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Number of items per page (1-100)",
     *          required=false,
     *          @OA\Schema(type="integer", minimum=1, maximum=100)
     *      ),
     *      @OA\Parameter(
     *          name="trashed",
     *          in="query",
     *          description="Show only deleted users when true, active users when false/omitted (true/false/1/0)",
     *          required=false,
     *          @OA\Schema(type="string", enum={"true", "false", "1", "0"})
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *              @OA\Property(property="current_page", type="integer", example=1),
     *              @OA\Property(property="per_page", type="integer", example=15),
     *              @OA\Property(property="total", type="integer", example=100),
     *              @OA\Property(property="last_page", type="integer", example=7)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function index(IndexUserRequest $request): AnonymousResourceCollection
    {
        return UserResource::collection($this->userService->getAllUsers($request->validated()));
    }

    /**
     * Show a specific user resource
     *
     * @return User
     *
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="showUser",
     *      tags={"Users"},
     *      summary="Show a specific user",
     *      description="Returns a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     */
    public function show(int $userId): UserResource
    {
        return UserResource::make($this->userService->getUser($userId));
    }

    /**
     * Store a newly created user in storage.
     *
     * @return User
     *
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      tags={"Users"},
     *      summary="Store a new user",
     *      description="Stores a new user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(StoreUserRequest $request): UserResource
    {
        return UserResource::make($this->userService->createUser($request->validated()));
    }

    /**
     * Update a specific user resource
     *
     * @return User
     *
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update a specific user",
     *      description="Updates a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(UpdateUserRequest $request, int $userId): UserResource
    {
        return UserResource::make($this->userService->updateUser($request->validated(), $userId));
    }

    /**
     * Remove a specific user resource
     *
     * @return User
     *
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete a specific user",
     *      description="Deletes a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User deleted successfully")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     */
    public function destroy(int $userId): JsonResponse
    {
        $this->userService->deleteUser($userId);

        return response()->json([
            'message' => trans('messages.user_deleted')
        ]);
    }

    /**
     * Restore a soft deleted user
     *
     * @return User
     *
     * @OA\Post(
     *      path="/users/{id}/restore",
     *      operationId="restoreUser",
     *      tags={"Users"},
     *      summary="Restore a soft deleted user",
     *      description="Restores a soft deleted user by setting deleted_at to null",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User restored successfully - deleted_at will be null",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", ref="#/components/schemas/User", 
     *                  example={
     *                      "id": 1,
     *                      "name": "John Doe",
     *                      "email": "john@example.com",
     *                      "email_verified_at": null,
     *                      "created_at": "2021-01-01T00:00:00.000000Z",
     *                      "updated_at": "2021-01-01T00:00:00.000000Z",
     *                      "deleted_at": null
     *                  }
     *              )
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     */
    public function restore(int $userId): UserResource
    {
        return UserResource::make($this->userService->restoreUser($userId));
    }
}

