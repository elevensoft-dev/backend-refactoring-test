<?php

namespace App\Core\Services\User;

use App\Http\Request\UserListingRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface IUserListingService
{
    public function paginateUsers(UserListingRequest $request): AnonymousResourceCollection;
    public function getUserById(int $id): UserResource;
}
