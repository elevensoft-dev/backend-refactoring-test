<?php

namespace App\Service\User;

use App\Models\User;
use App\Repository\User\Contracts\UserRepositoryInterface;
use App\Service\User\Contracts\UserServiceInterface;
use Illuminate\Support\Collection;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->all();
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getById($id);
    }

    public function storeNewUser(array $data): ?User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, int $id): ?User
    {
        return $this->userRepository->update($data, $id);
    }

    public function deleteUser(int $id): ?User
    {
        return $this->userRepository->delete($id);
    }
}
