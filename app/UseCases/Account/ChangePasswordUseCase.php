<?php

declare(strict_types=1);

namespace App\UseCases\Account;

use App\Models\User;
use App\UseCases\Account\DTOs\ChangePasswordDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ChangePasswordUseCase
{
    public function handle(ChangePasswordDto $dto): void
    {
        $user = User::find($dto->userId);

        if (! $user) {
            throw new ModelNotFoundException('User not found');
        }

        $user->password = $dto->password;
        $user->save();
    }
}
