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
}
