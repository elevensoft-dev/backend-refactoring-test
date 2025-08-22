<?php

namespace App\Providers\DependencyInjection;

use App\Core\Repositories\IUserRepository;
use App\Core\Services\User\IUserListingService;
use App\Domain\Repositories\UserRepository;
use App\Domain\Services\User\UserListingService;

class UserDi extends DependencyInjection
{
    protected function servicesConfiguration(): array
    {
        return [
            [IUserListingService::class, UserListingService::class],
        ];
    }

    protected function repositoriesConfigurations(): array
    {
        return [
            [IUserRepository::class, UserRepository::class]
        ];
    }
}
