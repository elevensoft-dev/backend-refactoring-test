<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\UpdateUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Put(
 *     path="/v1/users/update/{id}",
 *     summary="Atualizar um usuário",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do usuário",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Teste Nome Atualizado"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuário atualizado com sucesso",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Usuário não encontrado",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class UpdateUserController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function __invoke(int $id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->updateUser($data, $id);

        return $this->successResponse($user, 'User updated successfully');
    }
}
