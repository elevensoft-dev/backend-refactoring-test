<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

/**
 * Return a list of paginated users
 * @OA\Get(
 *     path="/v1/users/all/paginate",
 *     summary="Listar usuários paginados",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Número da página",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Quantidade de itens por página",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuários paginada",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessPaginatedResponse")
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
class GetAllUsersPaginatedController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private UserServiceInterface $userService
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $usersPaginated = $this->userService->getAllUsersPaginated();

        return $this->paginationSuccessResponse(
            $usersPaginated,
            'Users retrieved successfully'
        );
    }
}
