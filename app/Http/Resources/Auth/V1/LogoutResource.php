<?php

namespace App\Http\Resources\Auth\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LogoutResource",
 *     type="object",
 *     title="Logout Resource",
 *     @OA\Property(property="logout", type="boolean", example="true")
 * )
 */
class LogoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'logout' => $this['token_revoked'],
        ];
    }
}
