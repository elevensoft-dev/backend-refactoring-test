<?php

namespace App\Http\Controllers\Get\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

class AllUsersController extends Controller
{
    use SuccessResponsesTrait;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(): JsonResponse
    {
        $users = $this->userService->getAllUsers()->toArray();

        return $this->jsonSuccessResponse($users, 'Users retrieved successfully');
    }
}
