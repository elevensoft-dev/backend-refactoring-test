<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

/**
 * Return a list of users
 *
 * @OA\Get(
 *     path="/v1/users/all",
 *     summary="Listar usuários",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuários",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Nenhum usuário encontrado",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class GetAllUsersController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function __invoke(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return $this->successResponse(
            $users,
            'Users retrieved successfully'
        );
    }
}
