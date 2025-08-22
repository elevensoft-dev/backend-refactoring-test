<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class QueryFilterService
{
    /**
     * Function to filter models by their defined filterable fields
     *
     * @param  array  $validated  Validated fields from the request
     * @param  array  $filterable  Filterable fields from the model
     */
    public function applyFilters(Builder $query, array $validated, array $filterable): Builder
    {
        foreach ($validated as $filter => $value) {
            $column = $filterable[$filter]['column'];
            $method = $filterable[$filter]['method'];
            if ($method == 'like') {
                $query->where($column, 'LIKE', '%'.$value.'%');
            } else {
                $query->where($column, $value);
            }
        }

        return $query;
    }
}
