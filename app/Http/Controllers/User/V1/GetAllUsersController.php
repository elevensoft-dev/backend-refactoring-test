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
 *     summary="List all users",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Response(
 *         response=200,
 *         description="User list",
 *         @OA\JsonContent(ref="#/components/schemas/AllUsersSuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Not authorized",
 *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Users not found",
 *         @OA\JsonContent(ref="#/components/schemas/UsersNotFoundResponse")
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
            'Users retrieved successfully.'
        );
    }
}
