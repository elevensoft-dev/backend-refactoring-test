<?php

namespace App\Http\Controllers;

use App\Core\Services\User\IUserCreateService;
use App\Core\Services\User\IUserDeleteService;
use App\Core\Services\User\IUserListingService;
use App\Core\Services\User\IUserUpdateService;
use App\Http\Request\UserCreateRequest;
use App\Http\Request\UserListingRequest;
use App\Http\Request\UserUpdateRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private IUserListingService $userListingService,
        private IUserCreateService $userCreateService,
        private IUserUpdateService $userUpdateService,
        private IUserDeleteService $userDeleteService,
    ) {}

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
    public function createUser(UserCreateRequest $request): JsonResponse
    {
        return response()->json(
            $this->userCreateService->createUser($request),
            201
        );
    }
    public function updateUser(UserUpdateRequest $request, int $id): JsonResponse
    {
        return response()->json(
            $this->userUpdateService->updateUser($id, $request),
            200
        );
    }
    public function deleteUser(int $id): JsonResponse
    {
        $this->userDeleteService->deleteUser($id);
        return response()->json(null, 204);
    }
}
