<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {

    protected $repository;
    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllUser() {
        $data =$this->repository->all();

        if (empty($data)) {
            return ['status' => false, 'data' => $data];
        }

        return ['status' => true, 'data' => $data];
    }

    public function getUserById(int $id) {
        $data = $this->repository->find($id);

        if (empty($data)) {
            return ['status' => false, 'data' => $data];
        }

        return ['status' => true, 'data' => $data];
    }

    public function createUser(array $data) {
        $data['password'] = bcrypt($data['password']);
        $data = $this->repository->create($data);
        if (empty($data)) {
            return ['status' => false, 'data' => $data];
        }

        return ['status' => true, 'data' => $data];
    }

    public function updateUser(int $id, array $data) {
        $user = $this->repository->find($id);
        if (empty($user)) {
            return ['status' => false, 'data' => 'User not found'];
        }

        $data = $this->repository->update($user, $data);

        if (empty($data)) {
            return ['status' => false, 'data' => $data];
        }

        return ['status' => true, 'data' => $data];
    }

    public function deleteUser(int $id) {
        $user = $this->repository->find($id);
        if (empty($user)) {
            return ['status' => false, 'data' => 'User not found'];
        }

        $result = $this->repository->delete($id);
        return ['status' => $result, 'data' => 'User deleted successfully'];
    }
}
