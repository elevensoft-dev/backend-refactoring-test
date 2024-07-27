<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function register(array $payload): JsonResponse
    {
        $user = User::create($payload);

        $user['bearer_token'] = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'User has been registered successfully.',
            'data' => $user,
        ], 201);
    }
    public static function attempt(array $credentials): JsonResponse
    {
        if (Auth::attempt($credentials)) {

            $user =  Auth::user();

            $user->tokens()->delete();

            $user['bearer_token'] = $user->createToken($user->email)->plainTextToken;

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged successfully.',
                'data' => $user,
            ], 200);
        }

        return response()->json(['email' => 'Credentials invalid'], 401);
    }
    public static function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 204,
            'message' => 'Logged out successfully.',
        ], 204);
    }
    public static function refreshToken(): JsonResponse
    {
        $user =  Auth::user();

        $user->tokens()->delete();

        $user['bearer_token'] = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'User has been logged successfully.',
            'data' => $user,
        ], 200);
    }
}
