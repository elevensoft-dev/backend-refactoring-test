<?php

namespace App\Providers\V1;

use App\Repository\BaseRepository;
use App\Repository\BaseRepositoryInterface;
use App\Repository\User\V1\Contracts\UserRepositoryInterface;
use App\Repository\User\V1\UserRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            BaseRepositoryInterface::class,
            BaseRepository::class,
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
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
            BaseRepositoryInterface::class,
            UserRepositoryInterface::class,
        ];
    }
}
