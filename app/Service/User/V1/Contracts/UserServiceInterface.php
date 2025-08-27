<?php

namespace App\Service\User\V1\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(): array;

    public function getAllUsersPaginated(): ?LengthAwarePaginator;

    public function getUserById(int $id): array;

    public function storeNewUser(array $data): array;

    public function updateUser(array $data, int $id): array;

    public function deleteUser(int $id): array;
}
