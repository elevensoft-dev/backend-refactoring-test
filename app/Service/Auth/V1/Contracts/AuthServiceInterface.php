<?php

namespace App\Service\Auth\V1\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

interface AuthServiceInterface
{
    public function getAccessToken(array $credentials): JsonResource;

    public function revokeToken(Request $request): JsonResource;
}
