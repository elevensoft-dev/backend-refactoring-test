<?php

namespace App\Http\Controllers;

use App\Core\Services\User\IUserCreateService;
use App\Core\Services\User\IUserDeleteService;
use App\Core\Services\User\IUserListingService;
use App\Core\Services\User\IUserUpdateService;
use App\Http\Request\UserCreateRequest;
use App\Http\Request\UserListingRequest;
use App\Http\Request\UserUpdateRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Operações de gerenciamento de usuários"
 * )
 */
class UserController extends Controller
{
    public function __construct(
        private IUserListingService $userListingService,
        private IUserCreateService $userCreateService,
        private IUserUpdateService $userUpdateService,
        private IUserDeleteService $userDeleteService,
    ) {}
    /**
     * @OA\Post(
     *     path="/api/users/list",
     *     tags={"Users"},
     *     summary="Lista usuários com paginação",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="perPage", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function paginateUsers(UserListingRequest $request): JsonResponse
    {
        return response()->json(
            $this->userListingService->paginateUsers($request)
        );
    }
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Retorna usuário por ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário retornado",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function getUserById(int $id): JsonResponse
    {
        return response()->json(
            $this->userListingService->getUserById($id)
        );
    }
    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Cria um novo usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","passwordConfirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="passwordConfirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=400, description="Dados inválidos"),
     *     @OA\Response(response=409, description="Email já em uso"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function createUser(UserCreateRequest $request): JsonResponse
    {
        return response()->json(
            $this->userCreateService->createUser($request),
            201
        );
    }
    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Atualiza usuário por ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="passwordConfirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="Usuário não encontrado"),
     *     @OA\Response(response=400, description="Dados inválidos"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function updateUser(UserUpdateRequest $request, int $id): JsonResponse
    {
        return response()->json(
            $this->userUpdateService->updateUser($id, $request),
            200
        );
    }
    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Deleta usuário por ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Usuário deletado com sucesso"),
     *     @OA\Response(response=404, description="Usuário não encontrado"),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function deleteUser(int $id): JsonResponse
    {
        $this->userDeleteService->deleteUser($id);
        return response()->json(null, 204);
    }
}
