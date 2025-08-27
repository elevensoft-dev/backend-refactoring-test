<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

class GetAllUsersPaginatedController extends Controller
{
    use SuccessResponsesTrait;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
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
