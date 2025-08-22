<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserListingService;
use App\Http\Request\UserListingRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserListingService implements IUserListingService
{
    public function __construct(
        private IUserRepository $userRepository,
    )
    {
    }

    public function paginateUsers(UserListingRequest $request): AnonymousResourceCollection
    {
        $users = $this->userRepository->paginateUsers($request);
        return UserResource::collection($users);
    }
    public function getUserById(int $id): UserResource
    {
        $user = $this->userRepository->findUserById($id);
        if (!$user) {
            throw new HttpResponseException(response()->json(['message' => 'Usuario não encontrado.'], 404));
        }
        return new UserResource($user);
    }
}
