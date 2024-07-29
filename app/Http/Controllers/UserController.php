<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Request;


class UserController extends Controller
{
    private User $user;

    function __construct(User $user)
    {
        $this->user = $user;
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
    public function store(UserCreateRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'cpf' => $validatedData['cpf'],
            ]);

            return response()->json(['message' => 'Usuário cadastrado com sucesso.','user' => $user], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao criar usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a specific user resource
     *
     * @return User
     *
     * @OA\Put(
     *      path="/api/v1/users/{id}",
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
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            
            $validatedData = $request->validated();

            if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }
            
            $user->update($validatedData);
            $user->refresh();

            return response()->json(['message' => 'Usuário atualizado com sucesso.', 'user' => $user], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao atualizar usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
            $user->delete();
            return response()->json(['message' => 'Usuário excluído com sucesso.'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao excluir usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Show a specific user resource by CPF
     *
     * @return User
     *
     * @OA\Get(
     *      path="/users/cpf/{cpf}",
     *      operationId="showUserByCpf",
     *      summary="Show a specific user by CPF",
     *      tags={"Users"},
     *      description="Returns a specific user by CPF",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="cpf",
     *          description="User CPF",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
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
    public function showByCpf(string $cpf)
    {
        try {
            $user = User::where('cpf', $cpf)->firstOrFail();
            return response()->json($user, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a specific user resource by CPF
     *
     * @return User
     *
     * @OA\Put(
     *      path="/users/cpf/{cpf}",
     *      operationId="updateUserByCpf",
     *      summary="Update a specific user by CPF",
     *      tags={"Users"},
     *      description="Updates a specific user by CPF",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="cpf",
     *          description="User CPF",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
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
     *          response=404,
     *          description="User not found"
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
    public function updateByCpf(UserUpdateRequest $request, string $cpf)
    {
        try {
            $validatedData = $request->validated();

            if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            $user = User::where('cpf', $cpf)->firstOrFail();
            $user->update($validatedData);
            $user->refresh();

            return response()->json($user, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao atualizar usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove a specific user resource by CPF
     *
     * @return Response
     *
     * @OA\Delete(
     *      path="/users/cpf/{cpf}",
     *      operationId="deleteUserByCpf",
     *      summary="Delete a specific user by CPF",
     *      tags={"Users"},
     *      description="Deletes a specific user by CPF",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="cpf",
     *          description="User CPF",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
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
    public function destroyByCpf(string $cpf)
    {
        try {
            $user = User::where('cpf', $cpf)->firstOrFail();
            $user->delete();
            return response()->json(['message' => 'Usuário excluído com sucesso.'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao excluir usuário.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro inesperado.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

