<?php

namespace App\Providers\V1;

use App\Service\Auth\V1\AuthService;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Service\User\V1\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserServiceInterface::class,
            UserService::class,
        );

        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class,
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            UserServiceInterface::class,
            AuthServiceInterface::class,
        ];
    }
}
