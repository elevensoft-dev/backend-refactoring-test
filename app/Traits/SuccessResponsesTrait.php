<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponsesTrait
{
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
