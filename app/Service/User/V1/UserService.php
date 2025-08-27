<?php

namespace App\Service\User\V1;

use App\Exceptions\CollectionEmptyException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Resources\User\V1\UserPaginationCollection;
use App\Http\Resources\User\V1\UserResource;
use App\Repository\User\V1\Contracts\UserRepositoryInterface;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): ResourceCollection
    {
        $users = $this->userRepository->all();

        if ($users->isEmpty()) {
            throw new CollectionEmptyException('No users found in the database.', Response::HTTP_NOT_FOUND);
        }

        return UserResource::collection($users);
    }

    public function getAllUsersPaginated(): ResourceCollection
    {
        $usersPaginated = $this->userRepository->allPaginated();

        if ($usersPaginated->isEmpty()) {
            throw new CollectionEmptyException('No users found in the database.', Response::HTTP_NOT_FOUND);
        }

        return new UserPaginationCollection($usersPaginated);
    }

    public function getUserById(int $id): JsonResource
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        return new UserResource($user);
    }

    public function storeNewUser(array $data): JsonResource
    {
        $userCreated = $this->userRepository->create($data);

        return new UserResource($userCreated);
    }

    public function updateUser(array $data, int $id): JsonResource
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        $userUpdated = $this->userRepository->update($data, $user);

        return new UserResource($userUpdated);
    }

    public function deleteUser(int $id): JsonResource
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        $userDeleted = $this->userRepository->delete($user);

        return new UserResource($userDeleted);
    }
}
