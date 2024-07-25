<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{

    protected $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUser(): array
    {
        $result = $this->repository->all();

        if (empty($result)) {
            return $this->formatResponse(400, 'User not found');
        }

        return $this->formatResponse(200, $result);
    }

    public function getUserById(int $id): array
    {
        $result = $this->repository->find($id);

        if (empty($result)) {
            return $this->formatResponse(400, 'User not found');
        }

        return $this->formatResponse(200, $result);
    }

    public function createUser(array $data): array
    {
        $data['password'] = bcrypt($data['password']);
        $result = $this->repository->create($data);
        if (empty($result)) {
            return $this->formatResponse(400, 'User not created');
        }

        return $this->formatResponse(201, $result);
    }

    public function updateUser(int $id, array $data): array
    {
        $user = $this->repository->find($id);
        if (empty($user)) {
            return $this->formatResponse(400, 'User not found');
        }

        $result = $this->repository->update($user, $data);
        return $this->formatResponse(200, $result);
    }

    public function deleteUser(int $id): array
    {
        $user = $this->repository->find($id);
        if (!$user) {
            return $this->formatResponse(400, 'User not found');
        }

        $this->repository->delete($id);
        return $this->formatResponse(200, 'User deleted successfully');
    }

    private function formatResponse(int $status, $data): array
    {
        return [
            'status' => $status,
            'data' => $data
        ];
    }
}
