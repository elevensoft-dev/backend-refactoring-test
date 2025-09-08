<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Models\User;
use App\UseCases\Auth\DTOs\LoginUserDto;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

final class LoginUserUseCase
{
    public function handle(LoginUserDto $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return $user->createToken('api')->plainTextToken;
    }
}
