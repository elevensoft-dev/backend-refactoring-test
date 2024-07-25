<?php

namespace App\Repositories\Contract;

interface InterfaceRepository
{

    public function all(): object;

    public function find(int $id): ?object;

    public function create(array $data): object;

    public function update($data, array $request): object;

    public function delete(int $id): bool;
}
