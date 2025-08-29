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
 *     @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNThiZGIzZTM5NjgwYmIwNmRlNzZlZDRiZWVhZDEwNzZjMWY0NDNiZDA2ZDFlNWM2YWU1YzRmODhhNmY0ODI1ZDViZmVmMDNlNDk1MjZmZjAiLCJpYXQiOjE3NTYzMDE3MDYuNjAxOTY0LCJuYmYiOjE3NTYzMDE3MDYuNjAxOTY2LCJleHAiOjE3ODc4Mzc3MDYuNTk2MDExLCJzdWIiOiI2Iiwic2NvcGVzIjpbXX0.utz_uFS-DStlvZxrD9qrPpPZg7EPSfrz7iHRg0Ar8m1gTdzV09fPZ-6Zhry-676eD8HzGcr8C6RMUQn2Ed7drG3Hsg-g5QKnGBDptNwW5dYZGeoB8_Erwr-ecy9MqXhSY_twXnJIyL5Y9g2FAKz7k522JGsn7kWyPvwj07oCddoMKQAjzAjSOdyvjWLPLi4wqbJLTXxeMaHcy8aalUCtxtNAtorntiws1-WBxfVsoTTqjwPXfvj0ie0aHC0sloKWQjMYjA71jPfGEhYqtFPooTTr4BsiKM7i13zYBmYbsAg1GFgMBcpDjiAzYy4S_b4hj7VjB0EA1AdV58eORqSEg0lxhTco4apTZrySPo863zxdTWsyGnYatSlczququjBVKf_FTrJ8KVCQxsm5yrkCe_8dGQXVhWVPcpE1tuT96hJ0OfFPg73QFqH_5IaRG0re0yVkcFHun-iXcomjAjVEPW34l3VZJGKf5w-qUXsgGL4zTS79emkSZjyauq9c86tDtWA0C6ohwftmWbg_SHCf0ivjOpvH8XDmhw-wFbNM_byDmEYbItOLmMLb25mEfxotcPw6yVj_O27rdxFa2HRYkbF--8Jm3nqETkqCUld-i3_0Rm4NUejyL2pnqyTaEL7D53d-a1kd73YQXsCBg4Z5F0EahUMaW98fiuqQJQHr99Y"),
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
