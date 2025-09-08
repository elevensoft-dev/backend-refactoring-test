<?php

declare(strict_types=1);

namespace App\UseCases\Account\DTOs;

final class ChangePasswordDto
{
    public function __construct(
        public readonly int $userId,
        public readonly string $password,
    ) {}
}
