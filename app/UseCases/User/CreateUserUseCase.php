<?php

namespace App\UseCases\User;

use App\Models\User;
use App\UseCases\User\DTOs\CreateUserDto;

class CreateUserUseCase
{
    public function handle(CreateUserDto $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);
    }
}
