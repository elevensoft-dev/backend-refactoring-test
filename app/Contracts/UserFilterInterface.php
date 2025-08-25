<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface UserFilterInterface
{
    public function apply(Builder $builder, array $data): Builder;
    
    public function shouldApply(array $data): bool;
}
