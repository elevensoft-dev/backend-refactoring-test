<?php

namespace App\Http\Controllers;

use App\Core\Services\User\IUserCreateService;
use App\Core\Services\User\IUserDeleteService;
use App\Core\Services\User\IUserListingService;
use App\Core\Services\User\IUserUpdateService;
use App\Http\Request\UserCreateRequest;
use App\Http\Request\UserListingRequest;
use App\Http\Request\UserUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Js;

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
    public function updateUser(UserUpdateRequest $request, int $id): JsonResponse
    {
        try {
            return response()->json(
                $this->userUpdateService->updateUser($id, $request),
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function deleteUser(int $id): JsonResponse
    {
        try {
            return response()->json(
                $this->userDeleteService->deleteUser($id),
                204
            );
        } catch (HttpResponseException $e) {
            return response()->json([
                $e->getResponse()
            ], 400);
        }
    }
}
