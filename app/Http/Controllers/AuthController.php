<?php

namespace App\Http\Controllers;

use App\Core\Repositories\IAuthRepository;
use App\Http\Request\LoginAuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private IAuthRepository $authRepository) {}

    public function login(LoginAuthRequest $request): JsonResponse
    {
        return response()->json($this->authRepository->login($request));
    }
    public function logout(): JsonResponse
    {
        $this->authRepository->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
