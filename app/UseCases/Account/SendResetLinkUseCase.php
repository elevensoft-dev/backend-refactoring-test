<?php

declare(strict_types=1);

namespace App\UseCases\Account;

use App\Models\User;
use App\UseCases\Account\DTOs\SendResetLinkDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Password;
use RuntimeException;

final class SendResetLinkUseCase
{
    public function handle(SendResetLinkDto $dto): void
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user) {
            throw new ModelNotFoundException('User not found');
        }

        $response = Password::sendResetLink(['email' => $dto->email]);
        if ($response !== Password::RESET_LINK_SENT) {
            throw new RuntimeException('Unable to send reset link');
        }
    }
}
