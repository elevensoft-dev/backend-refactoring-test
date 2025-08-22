<?php

namespace App\Core\Repositories;

use App\Http\Request\LoginAuthRequest;

interface IAuthRepository
{
    public function login(LoginAuthRequest $request): array;
    public function logout(): void;
}
