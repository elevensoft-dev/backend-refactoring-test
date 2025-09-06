<?php

declare(strict_types=1);

namespace App\UseCases\User\DTOs;

class UpdateUserDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
    ) {}
}
