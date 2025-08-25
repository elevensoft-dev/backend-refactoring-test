<?php

namespace App\Services;

use App\Models\User;
use App\Services\UserFilterService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly User $user,
        private readonly UserFilterService $filterService
    ) {}

    /**
     * Get all users (active by default, only trashed when trashed=true)
     */
    public function getAllUsers(array $data): LengthAwarePaginator
    {
        $builder = $this->user->newQuery();
        $builder = $this->filterService->applyFilters($builder, $data);

        return $builder
            ->orderBy('created_at')
            ->paginate(data_get($data, 'per_page', User::PER_PAGE));
    }

    /**
     * Get a specific user
     */
    public function getUser(int $userId): User
    {
        return $this->user->findOrFail($userId);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        });
    }

    /**
     * Update an existing user
     */
    public function updateUser(array $data, int $userId): User
    {
        return DB::transaction(function () use ($data, $userId) {
            $user = $this->getUser($userId);

            $password = data_get($data, 'password');
            
            if ($password) {
                $data['password'] = Hash::make($password);
            }

            $user->update($data);
            $user->refresh();

            return $user;
        });
    }

    /**
     * Soft delete a user
     */
    public function deleteUser(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            $this->getUser($userId)->delete();
        });
    }

    /**
     * Restore a soft deleted user
     */
    public function restoreUser(int $userId): User
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->user->withTrashed()->findOrFail($userId);
            $user->deleted_at = null;
            $user->save();
            
            return $user;
        });
    }
}
