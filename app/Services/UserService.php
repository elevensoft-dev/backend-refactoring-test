<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserService
{
    /**
     * Create a new class instance.
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function store(array $payload): JsonResponse
    {

        $user = $this->user->create($payload);

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'Users created successfully!',
            ],
            'data' => [
                'user' => $user
            ],
        ], Response::HTTP_OK);
    }
    public function show(?int $id): JsonResponse
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'fails',
                    'message' => 'User not found!',
                ],
                'data' => [
                    'user' => []
                ],
                'access_token' => [],
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'User find successfully!',
            ],
            'data' => [
                'user' => $user
            ],
        ], Response::HTTP_OK);
    }
    public function delete(?int $id): JsonResponse
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'fails',
                    'message' => 'User not found!',
                ],
                'data' => [
                    'user' => []
                ],
                'access_token' => [],
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'User deleted successfully!',
            ],
            'data' => [
                'user' => $user
            ],
        ], Response::HTTP_OK);
    }
}
