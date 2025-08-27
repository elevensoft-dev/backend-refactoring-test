<?php

namespace App\Service\Auth\V1\Contracts;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function getAccessToken(array $credentials): array;

    public function revokeToken(Request $request): bool;
}
