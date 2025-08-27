<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponsesTrait
{
    private function successResponse(
        array $data,
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
        LengthAwarePaginator $paginatedData,
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
