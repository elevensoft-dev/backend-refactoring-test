<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class AuthenticateController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return AuthServices::register($validated);
    }

    public function attempt(LoginRequest $request): JsonResponse
    {

        $validated = $request->validated();

        return AuthServices::attempt($validated);
    }
    public function logout(): JsonResponse
    {
        return AuthServices::logout();
    }
    public function refreshToken(): JsonResponse
    {
        return AuthServices::refreshToken();
    }
}
