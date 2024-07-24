<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {

    protected $repository;
    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllUser() {
        return $this->repository->all();
    }

    public function getUserById(int $id) {
        return $this->repository->find($id);
    }

    public function createUser(array $data) {
        return $this->repository->create($data);
    }

    public function updateUser(int $id, array $data) {
        return $this->repository->update($data, $id);
    }

    public function deleteUser(int $id) {
        return $this->repository->delete($id);
    }
}
