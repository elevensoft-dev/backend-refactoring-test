<?php

namespace App\Repositories\Contract;

abstract class AbstractRepository implements InterfaceRepository{
    protected $model;

    public function all()
    {
        return $this->model->all();
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        dd($data);
        return $this->model->create($data);
    }

    public function update($data, array $request)
    {
        $data->update($request);
        return $data;
    }

    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }
}
