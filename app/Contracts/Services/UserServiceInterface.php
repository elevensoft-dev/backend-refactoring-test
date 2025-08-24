<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection<User>
     */
    public function getAllUsers(): Collection;

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User;

    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function createUser(array $data): User;

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User|null
     */
    public function updateUser(int $id, array $data): ?User;

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool;
}
