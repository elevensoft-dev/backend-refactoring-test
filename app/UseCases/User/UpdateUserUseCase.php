<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Models\User;
use App\UseCases\User\DTOs\UpdateUserDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateUserUseCase
{
    public function handle(UpdateUserDto $dto): User
    {
        $user = User::find($dto->id);

        if (! $user) {
            throw new ModelNotFoundException('User not found');
        }

        $data = array_filter([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password ? $dto->password : null,
        ], fn ($value) => ! is_null($value));

        $user->update($data);

        $user->refresh();

        return $user;
    }
}
