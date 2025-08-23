<?php

namespace App\Http\Controllers;

use App\Core\Services\User\IUserCreateService;
use App\Core\Services\User\IUserListingService;
use App\Http\Request\UserCreateRequest;
use App\Http\Request\UserListingRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private IUserListingService $userListingService,
        private IUserCreateService $userCreateService,
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
        try {
            return response()->json(
                $this->userCreateService->createUser($request),
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
