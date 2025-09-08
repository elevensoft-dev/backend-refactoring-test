<?php

declare(strict_types=1);

namespace App\UseCases\Account;

use App\Models\User;
use App\UseCases\Account\DTOs\UpdateUserAccountDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UpdateProfileUseCase
{
    public function handle(UpdateUserAccountDto $dto): User
    {
        $user = User::find($dto->id);

        if (! $user) {
            throw new ModelNotFoundException('User not found');
        }

        $data = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
        ], fn ($value) => ! is_null($value));

        $user->update($data);
        $user->refresh();

        return $user;
    }
}
