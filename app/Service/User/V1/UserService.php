<?php

namespace App\Service\User\V1;

use App\Exceptions\CollectionEmptyException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\User;
use App\Repository\User\V1\Contracts\UserRepositoryInterface;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array
    {
        $users = $this->userRepository->all();

        if ($users->isEmpty()) {
            throw new CollectionEmptyException('No users found in the database.', Response::HTTP_NOT_FOUND);
        }

        return $users->toArray();
    }

    public function getAllUsersPaginated(): LengthAwarePaginator
    {
        $users = $this->userRepository->allPaginated();

        if ($users->isEmpty()) {
            throw new CollectionEmptyException('No users found in the database.', Response::HTTP_NOT_FOUND);
        }

        return $users;
    }

    public function getUserById(int $id): array
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        return $user->toArray();
    }

    public function storeNewUser(array $data): array
    {
        return $this->userRepository->create($data)->toArray();
    }

    public function updateUser(array $data, int $id): array
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        $userUpdated = $this->userRepository->update($data, $user);

        return $userUpdated->toArray();
    }

    public function deleteUser(int $id): array
    {
        $user = $this->userRepository->getById($id);

        if (! $user) {
            throw new ResourceNotFoundException('User not found.');
        }

        $userDeleted = $this->userRepository->delete($user);

        return $userDeleted->toArray();
    }
}
