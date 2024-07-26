<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getAllUsersPaginate($perPage, $page)
    {
        return User::paginate($perPage, ['*'], 'page', $page);
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function searchUserByColumn($column, $value) {

        return User::where($column, 'LIKE', "%{$value}%")->get();
    }

    public function deleteUser($userId)
    {
        User::find($userId)->delete();
    }

    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

    public function updateUser($userId, array $newDetails)
    {
        return User::find($userId)->update($newDetails);
    }
}
