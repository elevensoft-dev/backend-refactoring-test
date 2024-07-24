<?php

namespace App\Repositories\Contract;

abstract class AbstractRepository implements InterfaceRepository{
    protected $model;

    public function all()
    {
        $this->model->all();
    }

    public function find(int $id)
    {
        $this->model->find($id);
    }

    public function create(array $data)
    {
        $this->model->create($data);
    }

    public function update(array $data, int $id)
    {
        $this->model->update($data, $id);
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
    }
}
