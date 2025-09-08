<?php

declare(strict_types=1);

namespace App\UseCases\Account;

use App\UseCases\Account\DTOs\ResetPasswordDto;
use Illuminate\Support\Facades\Password;
use RuntimeException;

final class ResetPasswordUseCase
{
    public function handle(ResetPasswordDto $dto): void
    {
        $response = Password::reset(
            [
                'email' => $dto->email,
                'token' => $dto->token,
                'password' => $dto->password,
            ],
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            },
        );

        if ($response !== Password::PASSWORD_RESET) {
            throw new RuntimeException('Unable to reset password');
        }
    }
}
