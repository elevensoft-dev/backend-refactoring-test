<?php

namespace App\Service\User\V1\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public function getAllUsers(): ?Collection;

    public function getUserById(int $id): ?User;

    public function storeNewUser(array $data): ?User;

    public function updateUser(array $data, int $id): ?User;

    public function deleteUser(int $id): ?User;
}
