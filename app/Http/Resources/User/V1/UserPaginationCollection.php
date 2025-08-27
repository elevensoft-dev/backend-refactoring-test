<?php

namespace App\Http\Resources\User\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="UserPaginationResource",
 *     type="object",
 *     title="User Pagination Resource",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="from", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=10),
 *         @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/users"),
 *         @OA\Property(property="per_page", type="integer", example=10),
 *         @OA\Property(property="to", type="integer", example=10),
 *         @OA\Property(property="total", type="integer", example=100)
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(
 *             property="first",
 *             type="string",
 *             example="http://localhost:8000/api/v1/users?page=1"
 *         ),
 *         @OA\Property(
 *             property="last",
 *             type="string",
 *             example="http://localhost:8000/api/v1/users?page=10"
 *         ),
 *         @OA\Property(
 *             property="prev",
 *             type="string",
 *             nullable=true,
 *             example=null
 *         ),
 *         @OA\Property(
 *             property="next",
 *             type="string",
 *             nullable=true,
 *             example="http://localhost:8000/api/v1/users?page=2"
 *         )
 *     )
 * )
 */
class UserPaginationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => UserResource::collection($this->collection),
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'path' => $this->path(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
        ];
    }
}
