<?php

namespace App\Http\Resources\Auth\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LoginResource",
 *     type="object",
 *     title="Login Resource",
 *     @OA\Property(property="token_type", type="string", example="Bearer"),
 *     @OA\Property(property="access_token", type="string", example=""),
 * )
 */
class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token_type' => $this['token_type'],
            'access_token' => $this['access_token'],
        ];
    }
}
