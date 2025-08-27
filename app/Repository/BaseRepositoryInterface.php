<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * Get all records from database
     */
    public function all(): Collection;

    /**
     * Get all records from database and paginate it
     */
    public function allPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * Get a record by id
     */
    public function getById(int $id): ?Model;

    /**
     * Create a new record on database
     */
    public function create(array $data): Model;

    /**
     * Update a record
     */
    public function update(array $data, Model $model): Model;

    /**
     * Delete a record from database
     */
    public function delete(Model $model): Model;
}
