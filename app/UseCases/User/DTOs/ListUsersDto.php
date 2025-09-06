<?php

declare(strict_types=1);

namespace App\UseCases\User\DTOs;

final class ListUsersDto
{
    public function __construct(
        public readonly array $filters = [],
        public readonly int $perPage = 15,
    ) {}
}
