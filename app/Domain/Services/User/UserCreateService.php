<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserCreateService;
use App\Http\Request\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserCreateService implements IUserCreateService
{
    public function __construct(
        private IUserRepository $userRepository,
    )
    {
    }

    public function createUser(UserCreateRequest $request): UserResource
    {
        $userForCreation = $this->mapUser($request);
        $createdUser = $this->userRepository->createUser($userForCreation);
        return new UserResource($createdUser);
    }
    private function mapUser(UserCreateRequest $request): User
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        return $user;
    }
}
