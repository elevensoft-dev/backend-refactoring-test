<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponsesTrait
{
    /**
     * @OA\Schema(
     *     schema="SuccessResponse",
     *     type="object",
     *     title="Success Response",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         example=true
     *     ),
     *     @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Request was successful"
     *     ),
     *     @OA\Property(
     *         property="data",
     *         anyOf={
     *             @OA\Schema(ref="#/components/schemas/UserResource"),
     *             @OA\Schema(ref="#/components/schemas/UserPaginationResource"),
     *             @OA\Schema(ref="#/components/schemas/LoginResource"),
     *             @OA\Schema(ref="#/components/schemas/LogoutResource")
     *         }
     *
     *     )
     * )
     */
    private function successResponse(
        JsonResource $data,
        string $message = 'Request was successful',
        int $statusCode = Response::HTTP_OK,
    ): JsonResponse {
        $response = [
            'success' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * @OA\Schema(
     *     schema="SuccessPaginatedResponse",
     *     type="object",
     *     title="Success Paginated Response",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         example=true
     *     ),
     *     @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=200
     *     ),
     *     @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Request was successful"
     *     ),
     *     @OA\Property(
     *         property="pagination",
     *         @OA\Property(ref="#/components/schemas/UserPaginationResource")
     *     )
     * )
     */
    private function paginationSuccessResponse(
        ResourceCollection $paginatedData,
        string $message = 'Request was successful',
        int $statusCode = Response::HTTP_OK,
    ): JsonResponse {
        $response = [
            'success' => true,
            'code' => $statusCode,
            'message' => $message,
            'pagination' => $paginatedData,
        ];

        return response()->json($response, $statusCode);
    }
}
