<?php

declare(strict_types=1);

namespace App\UseCases\Account\DTOs;

final class UpdateUserAccountDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
    ) {}
}
