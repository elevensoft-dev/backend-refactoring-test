<?php

namespace App\Providers\DependencyInjection;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserCreateService;
use App\Core\Services\User\IUserDeleteService;
use App\Core\Services\User\IUserListingService;
use App\Core\Services\User\IUserUpdateService;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\UserCreateService;
use App\Domain\Services\User\UserDeleteService;
use App\Domain\Services\User\UserListingService;
use App\Domain\Services\User\UserUpdateService;

class UserDi extends DependencyInjection
{
    protected function servicesConfiguration(): array
    {
        return [
            [IUserListingService::class, UserListingService::class],
            [IUserCreateService::class, UserCreateService::class],
            [IUserUpdateService::class, UserUpdateService::class],
            [IUserDeleteService::class, UserDeleteService::class],
        ];
    }

    protected function repositoriesConfigurations(): array
    {
        return [
            [IUserRepository::class, UserRepository::class]
        ];
    }
}
