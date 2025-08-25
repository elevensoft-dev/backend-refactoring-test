<?php

namespace App\Services;

use App\Contracts\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class UserFilterService
{
    /**
     * @param UserFilterInterface[] $filters
     */
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function applyFilters(Builder $builder, array $data): Builder
    {
        foreach ($this->filters as $filter) {
            if ($filter->shouldApply($data)) {
                $builder = $filter->apply($builder, $data);
            }
        }

        return $builder;
    }
}
