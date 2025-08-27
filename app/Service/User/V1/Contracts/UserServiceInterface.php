<?php

namespace App\Service\User\V1\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

interface UserServiceInterface
{
    public function getAllUsers(): ResourceCollection;

    public function getAllUsersPaginated(): ResourceCollection;

    public function getUserById(int $id): JsonResource;

    public function storeNewUser(array $data): JsonResource;

    public function updateUser(array $data, int $id): JsonResource;

    public function deleteUser(int $id): JsonResource;
}
