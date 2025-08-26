<?php

namespace App\Http\Controllers\Delete\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

class DeleteUserController extends Controller
{
    use SuccessResponsesTrait;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(int $userId): JsonResponse
    {
        $user = $this->userService->deleteUser($userId)->toArray();

        return $this->jsonSuccessResponse($user, 'User deleted successfully');
    }
}
