<?php

namespace App\Service\Auth\V1;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\LogoutErrorException;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    public function getAccessToken(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];
    }

    public function revokeToken(Request $request): bool
    {
        $isRevoked = $request->user()->token()->revoke();

        if (! $isRevoked) {
            throw new LogoutErrorException();
        }

        return true;
    }
}
