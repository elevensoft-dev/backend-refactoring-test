<?php

namespace App\Repositories\Contract;

abstract class AbstractRepository implements InterfaceRepository
{
    protected $model;

    public function all(): object
    {
        return $this->model->all();
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update($data, array $request): object
    {
        $data->update($request);
        return $data;
    }

    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }
}
