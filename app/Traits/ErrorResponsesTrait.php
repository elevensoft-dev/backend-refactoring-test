<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ErrorResponsesTrait
{
    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        $response = [
            'success' => false,
            'code' => $statusCode,
            'error' => [
                'message' => $message,
            ],
        ];

        return response()->json($response, $statusCode);
    }

    private function errorsResponse(array $errors, int $statusCode): JsonResponse
    {
        $response = [
            'success' => false,
            'code' => $statusCode,
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }
}
