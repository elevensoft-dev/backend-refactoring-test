<?php

namespace App\Http\Controllers;

use App\Core\Services\User\IUserListingService;
use App\Http\Request\UserListingRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private IUserListingService $userListingService,
    )
    {
    }

    public function paginateUsers(UserListingRequest $request): JsonResponse
    {
        return response()->json(
            $this->userListingService->paginateUsers($request)
        );
    }
    public function getUserById(int $id): JsonResponse
    {
        return response()->json(
            $this->userListingService->getUserById($id)
        );
    }
}

