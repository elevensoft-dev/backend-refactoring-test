<?php
namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User management"
 * )
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index()
    {
        return response()->json($this->userService->getAllUsers(), 200);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     operationId="showUser",
     *     tags={"Users"},
     *     summary="Show a specific user",
     *     description="Returns a specific user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return $user
            ? response()->json($user, 200)
            : response()->json(['message' => 'User not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     operationId="storeUser",
     *     tags={"Users"},
     *     summary="Store a new user",
     *     description="Stores a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     summary="Update a specific user",
     *     description="Updates a specific user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {

        $user = $this->userService->updateUser($id, $request->validated());
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);

    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete a specific user",
     *     description="Deletes a specific user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);
        return $deleted
            ? response()->json(['message' => 'User deleted'], 200)
            : response()->json(['message' => 'User not found'], 404);
    }
}

