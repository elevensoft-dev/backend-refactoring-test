<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Return a list of paginated users
 * @OA\Get(
 *     path="/v1/users/all/paginate",
 *     summary="User list paginated",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="per-page",
 *         in="query",
 *         description="Items per page",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paginated user list",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessPaginatedResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Not authorized",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
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

    public function __invoke(Request $request): JsonResponse
    {
        $itemsPerPage = $request->query->get('per-page');

        $usersPaginated = $this->userService->getAllUsersPaginated($itemsPerPage);

        return $this->paginationSuccessResponse(
            $usersPaginated,
            'Users retrieved successfully.'
        );
    }
}
