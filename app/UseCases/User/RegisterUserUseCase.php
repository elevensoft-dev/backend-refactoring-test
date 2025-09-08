<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Models\User;
use App\UseCases\User\DTOs\CreateUserDto;

class RegisterUserUseCase
{
    public function handle(CreateUserDto $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        return $user;
    }
}
