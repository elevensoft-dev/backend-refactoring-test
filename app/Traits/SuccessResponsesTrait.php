<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponsesTrait
{
    private function jsonSuccessResponse(
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
}
