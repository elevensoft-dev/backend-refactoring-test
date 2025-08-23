<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserDeleteService;
use App\Exceptions\UserNotFoundException;

class UserDeleteService implements IUserDeleteService
{
    public function __construct(
        private IUserRepository $userRepository
    )
    {
    }

    public function deleteUser(int $id): bool
    {
        $userForDelete = $this->userRepository->findUserById($id);
        if (!$userForDelete) {
            throw new UserNotFoundException();
        }
        return $this->userRepository->deleteUser($id);
    }
}
