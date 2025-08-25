<?php

namespace App\Providers;

use App\Contracts\UserFilterInterface;
use App\Filters\SearchUsersFilter;
use App\Filters\TrashedUsersFilter;
use App\Services\UserFilterService;
use Illuminate\Support\ServiceProvider;

class UserFilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserFilterService::class, function ($app) {
            return new UserFilterService([
                $app->make(TrashedUsersFilter::class),
                $app->make(SearchUsersFilter::class),
            ]);
        });
    }
}
