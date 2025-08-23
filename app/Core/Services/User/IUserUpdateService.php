<?php

namespace App\Core\Services\User;

use App\Http\Request\UserUpdateRequest;
use App\Http\Resources\UserResource;

interface IUserUpdateService
{
    public function updateUser(int $id, UserUpdateRequest $request): ?UserResource;
}
