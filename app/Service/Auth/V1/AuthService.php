<?php

namespace App\Service\Auth\V1;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\LogoutErrorException;
use App\Http\Resources\Auth\V1\LoginResource;
use App\Http\Resources\Auth\V1\LogoutResource;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    public function getAccessToken(array $credentials): JsonResource
    {
        if (! Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->accessToken;

        $loginData = [
            'token_type' => 'Bearer',
            'access_token' => $token,
        ];

        return new LoginResource($loginData);
    }

    public function revokeToken(Request $request): JsonResource
    {
        $isRevoked = $request->user()->token()->revoke();

        if (! $isRevoked) {
            throw new LogoutErrorException();
        }

        $logoutData = [
            'token_revoked' => $isRevoked,
        ];

        return new LogoutResource($logoutData);
    }
}
