<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function attempt(array $credentials): JsonResponse
    {

        if (!Auth::attempt($credentials)) {

            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'status' => 'fail',
                    'message' => 'The provided credentials are incorrect.',
                ],
                'data' => [
                    'user' => []
                ],
                'access_token' => [
                    'token' => '',
                    'type' => 'Bearer'
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        $this->revokingTokens();

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_ACCEPTED,
                'status' => 'success',
                'message' => 'Login success.',
            ],
            'data' => [
                'user' => Auth::user()
            ],
            'access_token' => [
                'token' => Auth::user()->createToken($credentials['email'])->plainTextToken,
                'type' => 'Bearer'
            ],
        ], Response::HTTP_ACCEPTED);
    }
    public function logout(): JsonResponse
    {
        static::revokingTokens();

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_ACCEPTED,
                'status' => 'success',
                'message' => 'Successfully logged out',
            ],
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }
    public function revokingTokens(?int $id = null): void
    {
        if ($id) {
            Auth::user()->tokens('id', $id)->delete();
        }
        Auth::user()->tokens()->delete();
    }
}
