<?php

namespace App\Core\Repositories;

use App\Http\Request\UserListingRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IUserRepository
{
    public function paginateUsers(UserListingRequest $request): LengthAwarePaginator;
    public function findUserById(int $id): ?User;
    public function createUser(User $user): User;
}
