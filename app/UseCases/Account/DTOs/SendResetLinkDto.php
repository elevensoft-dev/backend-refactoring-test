<?php

declare(strict_types=1);

namespace App\UseCases\Account\DTOs;

final class SendResetLinkDto
{
    public function __construct(
        public readonly string $email,
    ) {}
}
