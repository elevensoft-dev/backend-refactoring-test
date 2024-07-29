<?php

namespace App\Interfaces\User;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function getUserById($id);
    public function getAllUsersPaginate($perPage, $page);
    public function getUserByEmail($email);
    public function searchUserByColumn($column, $value);
    public function deleteUser($id);
    public function createUser(array $userInfo);
    public function updateUser($id, array $newInfo);
}
