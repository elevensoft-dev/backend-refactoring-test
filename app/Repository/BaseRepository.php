<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function all(
        $columns = ['*'],
        array $relations = [],
        bool $paginate = false,
        int $perPage = 15,
    ): Collection|LengthAwarePaginator {
        $query = $this->model->with($relations)->select($columns);

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model
            ->with($relations)
            ->select($columns)
            ->findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, int $id): ?Model
    {
        $result = $this->model->findOrFail($id);

        $result->update($data);

        $result->refresh();

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): ?Model
    {
        $result = $this->model->findOrFail($id);

        $result->delete();

        return $result;
    }
}
