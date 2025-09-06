<?php

declare(strict_types=1);

namespace App\UseCases\Account\DTOs;

final class ResetPasswordDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $token,
        public readonly string $password,
    ) {}
}
