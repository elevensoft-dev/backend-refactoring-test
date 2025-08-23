<?php

namespace App\Core\Services\User;

interface IUserDeleteService
{
    public function deleteUser(int $id): bool;
}
