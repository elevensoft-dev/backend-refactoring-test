<?php

namespace App\Core\Services\User;

use App\Http\Request\UserCreateRequest;
use App\Http\Resources\UserResource;

interface IUserCreateService
{
    public function createUser(UserCreateRequest $request): UserResource;
}
