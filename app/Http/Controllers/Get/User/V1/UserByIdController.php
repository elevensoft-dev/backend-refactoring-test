<?php

namespace App\Http\Controllers\Get\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

class UserByIdController extends Controller
{
    use SuccessResponsesTrait;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id)->toArray();

        return $this->jsonSuccessResponse($user, 'User retrieved successfully');
    }
}
