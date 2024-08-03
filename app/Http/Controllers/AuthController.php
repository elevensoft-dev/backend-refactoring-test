<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Api\Swagger\Auth\AuthSwagger;

class AuthController extends Controller
{
    use AuthSwagger;

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        return (new AuthService)->attempt($credentials);
    }
    public function logout(): JsonResponse
    {
        return (new AuthService)->logout();
    }
}
