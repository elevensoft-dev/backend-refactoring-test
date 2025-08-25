<?php

namespace App\Filters;

use App\Contracts\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class TrashedUsersFilter implements UserFilterInterface
{
    public function apply(Builder $builder, array $data): Builder
    {
        return $builder->onlyTrashed();
    }
    
    public function shouldApply(array $data): bool
    {
        return filter_var(data_get($data, 'trashed', false), FILTER_VALIDATE_BOOLEAN);
    }
}
