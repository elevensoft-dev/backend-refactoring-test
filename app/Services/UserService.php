<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

        Auth::login($user);

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'Users created successfully!',
            ],
            'data' => [
                'user' => $user
            ],
            'access_token' => [
                'token' => $user->createToken($payload['email'])->plainTextToken,
                'type' => 'Bearer'
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
    public function update(array $payload, int $id): JsonResponse
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

        $user = $user->update($payload);

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'Users updated successfully!',
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

        $user->delete();

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'message' => 'User deleted successfully!',
            ],
            'data' => [
                'user' => []
            ],
        ], Response::HTTP_OK);
    }
}
