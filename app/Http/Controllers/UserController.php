<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ValidateUserRequest;


class UserController extends Controller
{
    protected $service;

    function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     *
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      summary="Get list of users",
     *      tags={"Users"},
     *      description="Returns list of users",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  ref="#/components/schemas/User"
     *              )
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
    public function index(): JsonResponse
    {
        $data = $this->service->getAllUser();
        return $this->generateResponse($data);
    }

    /**
     * Show a specific user resource
     *
     * @return User
     *
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="showUser",
     *      summary="Show a specific user",
     *      tags={"Users"},
     *      description="Returns a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
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
    public function show(int $id): JsonResponse
    {
        $data = $this->service->getUserById($id);
        return $this->generateResponse($data);
    }

    /**
     * Store a newly created user in storage.
     *
     * @return User
     *
     * @OA\Post(
     *      path="/users",
     *      operationId="storeUser",
     *      summary="Store a new user",
     *      tags={"Users"},
     *      description="Stores a new user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
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
    public function store(ValidateUserRequest $request): JsonResponse
    {
        $data = $this->service->createUser($request->all());
        return $this->generateResponse($data);
    }

    /**
     * Update a specific user resource
     *
     * @return User
     *
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      summary="Update a specific user",
     *      tags={"Users"},
     *      description="Updates a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
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
    public function update(int $id, ValidateUserRequest $request): JsonResponse
    {
        $data = $this->service->updateUser($id, $request->all());
        return $this->generateResponse($data);
    }

    /**
     * Remove a specific user resource
     *
     * @return User
     *
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      summary="Delete a specific user",
     *      tags={"Users"},
     *      description="Deletes a specific user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
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
    public function destroy(int $id): JsonResponse
    {
        $data = $this->service->deleteUser($id);
        return $this->generateResponse($data);
    }

    private function generateResponse(array $data): JsonResponse
    {
        return response()->json($data['data'], $data['status']);
    }
}

