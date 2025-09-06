<?php

declare(strict_types=1);

namespace App\UseCases\Auth\DTOs;

final class LoginUserDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
