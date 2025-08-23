<?php

namespace App\Domain\Repositories;

use App\Core\Repositories\IUserRepository;
use App\Http\Request\UserListingRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements IUserRepository
{
    public function paginateUsers(UserListingRequest $request): LengthAwarePaginator
    {
        $perPage = $request->perPage ?? 15;
        $page = $request->input('page', 1);
        return User::query()
            ->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn($q) => $q->where('email', 'like', "%{$request->email}%"))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
    public function findUserById(int $id): ?User
    {
        return User::query()->find($id);
    }
    public function createUser(User $user): User
    {
        $user->save();
        return $user;
    }
}
