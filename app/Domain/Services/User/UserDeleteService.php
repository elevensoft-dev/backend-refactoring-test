<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserDeleteService;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            throw new HttpResponseException(response()->json(['message' => 'Usuario não encotrado'], 404));
        }
        return $this->userRepository->deleteUser($id);
    }
}
