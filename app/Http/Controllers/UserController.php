<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\Update;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private User $user;

    function __construct(User $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');
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
    public function index(Request $request)
    {
        return $this->user->get();
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
    public function show(User $user)
    {
        return $user;
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
    public function store(RegisterRequest $request)
    {
        $data = $request->validate();

        return $this->user->create($data);
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
    public function update(Update $request, User $user)
    {
        $data = $request->validate();

        $user->update($data);

        return $user;
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
    public function destroy(User $user)
    {
        $user->delete();

        return $user;
    }
}
