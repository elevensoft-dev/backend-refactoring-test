<?php

namespace App\Repositories\Contract;

interface InterfaceRepository {

    public function all();

    public function find(int $id);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);
}
