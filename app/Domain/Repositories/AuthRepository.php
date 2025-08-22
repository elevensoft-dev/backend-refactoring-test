<?php

namespace App\Domain\Repositories;

use App\Core\Repositories\IAuthRepository;
use App\Http\Request\LoginAuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements IAuthRepository
{
    public function login(LoginAuthRequest $request): array
    {
        $credentials = $request->only(['email', 'password']);
        if (! Auth::attempt($credentials)) {
            throw new \Exception('Credenciais inválidas', 401);
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;
        $expiration = $tokenResult->token->expires_at;
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $expiration,
            'user' => [
                "name" => $user->name,
                "email" => $user->email,
            ],
        ];
    }
    public function logout(): void
    {
        $user = auth()->user();
        if ($user) {
            $user->tokens->each(function ($token) {
                $token->revoke();
            });
        }
    }
}
