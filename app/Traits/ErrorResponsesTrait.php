<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ErrorResponsesTrait
{
    private function jsonErrorResponse(string $message, int $statusCode): JsonResponse
    {
        $response = [
            'status' => 'error',
            'code' => $statusCode,
            'error' => [
                'message' => $message,
            ],
        ];

        return response()->json($response, $statusCode);
    }

    private function jsonErrorsResponse(array $errors, int $statusCode): JsonResponse
    {
        $response = [
            'status' => 'error',
            'code' => $statusCode,
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }
}
