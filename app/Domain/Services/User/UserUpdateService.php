<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserUpdateService;
use App\Exceptions\InvalidUserDataException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUpdateFailedException;
use App\Http\Request\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserUpdateService implements IUserUpdateService
{
    public function __construct(
        private IUserRepository $userRepository,
    ) {}

    public function updateUser(int $id, UserUpdateRequest $request): UserResource
    {
        if (empty($request->all())) {
            throw new InvalidUserDataException('Nenhum dado enviado para atualização');
        }
        $userForUpdate = $this->userRepository->findUserById($id);
        if (!$userForUpdate) {
            throw new UserNotFoundException();
        }
        $updateData = new User();
        if ($request->filled('name')) {
            $updateData->name = $request->name;
        }
        if ($request->filled('password')) {
            $updateData->password = bcrypt($request->password);
        }
        $updated = $this->userRepository->updateUser($updateData, $id);
        if (!$updated) {
            throw new UserUpdateFailedException();
        }
        return new UserResource($this->userRepository->findUserById($id));
    }
}
