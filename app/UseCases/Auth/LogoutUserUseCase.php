<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

final class LogoutUserUseCase
{
    public function handle(Authenticatable $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
