<?php

namespace App\Providers\DependencyInjection;

use App\Core\Repositories\IAuthRepository;
use App\Domain\Repositories\AuthRepository;


class AuthDi extends DependencyInjection
{
    protected function servicesConfiguration(): array
    {
        return [

        ];
    }

    protected function repositoriesConfigurations(): array
    {
        return [
            [IAuthRepository::class, AuthRepository::class]
        ];
    }
}
