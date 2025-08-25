<?php

namespace App\Filters;

use App\Contracts\UserFilterInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SearchUsersFilter implements UserFilterInterface
{
    public function __construct(
        private readonly User $user
    ) {}

    public function apply(Builder $builder, array $data): Builder
    {
        $search = data_get($data, 'search');
        return $this->user->searchFilter($builder, $search);
    }
    
    public function shouldApply(array $data): bool
    {
        return !empty(data_get($data, 'search'));
    }
}
