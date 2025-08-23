<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      summary="Get list of users",
     *      tags={"Users"},
     *      description="Returns paginated list of users",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        return UserResource::collection(User::paginate(15))->response();
    }

    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="showUser",
     *      summary="Show a specific user",
     *      tags={"Users"},
     *      description="Returns a specific user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(name="id", description="User ID", required=true, in="path"),
     *      @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/User")),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function show(User $user): JsonResponse
    {
        return (new UserResource($user))->response();
    }

    /**
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      summary="Store a new user",
     *      tags={"Users"},
     *      description="Stores a new user",
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *      @OA\Response(response=201, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/User")),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = bcrypt($data['password']); // segurança

        $user = User::create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      summary="Update a specific user",
     *      tags={"Users"},
     *      description="Updates a specific user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(name="id", description="User ID", required=true, in="path"),
     *      @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/User")),
     *      @OA\Response(response=200, description="Successful operation", @OA\JsonContent(ref="#/components/schemas/User")),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->only(['name', 'email', 'password']);
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        $user->refresh();

        return (new UserResource($user))->response();
    }


    /**
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      summary="Delete a specific user",
     *      tags={"Users"},
     *      description="Deletes a specific user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(name="id", description="User ID", required=true, in="path"),
     *      @OA\Response(response=204, description="No content"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
