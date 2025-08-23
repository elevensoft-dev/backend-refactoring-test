<?php

namespace App\Domain\Services\User;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserUpdateService;
use App\Http\Request\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateService implements IUserUpdateService
{
    public function __construct(
        private IUserRepository $userRepository,
    )
    {
    }

    public function updateUser(int $id, UserUpdateRequest $request): ?UserResource
    {
        if (empty($request->all())) {
            throw new HttpResponseException(response()->json(['message' => 'Dados inválidos'], 400));
        }
        $userForUpdate = $this->userRepository->findUserById($id);
        if (!$userForUpdate) {
            throw new HttpResponseException(response()->json(['message' => 'Usuario não encotrado'], 404));
        }
        $userForUpdate = $this->mapUserForUpdate($request);
        $bool = $this->userRepository->updateUser($userForUpdate, $id);
        if (!$bool) {
            throw new HttpResponseException(response()->json(['message' => 'Erro ao atualizar usuario'], 500));
        }
        return new UserResource($this->userRepository->findUserById($id));
    }
    private function mapUserForUpdate(UserUpdateRequest $request): User
    {
        $userForUpdate = new User();
        if (!empty($request->name)) {
            $userForUpdate->name = $request->name;
        }
        if (!empty($request->password)) {
            $userForUpdate->password = bcrypt($request->password);
        }
        return $userForUpdate;
    }
}
