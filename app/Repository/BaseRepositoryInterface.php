<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * Get all records from model
     */
    public function all(
        $columns = ['*'],
        array $relations = [],
        bool $paginate = false,
        int $perPage = 15
    ): Collection|LengthAwarePaginator;

    /**
     * Get a record by id
     */
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Model;

    /**
     * Create a new record on database
     */
    public function create(array $data): ?Model;

    /**
     * Update a record by id
     */
    public function update(array $data, int $id): ?Model;

    /**
     * Delete a record by id
     */
    public function delete(int $id): ?Model;
}
