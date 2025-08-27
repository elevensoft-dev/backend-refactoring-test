<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ErrorResponsesTrait
{
    /**
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     type="object",
     *     title="Error Response",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=404,
     *         description="Código HTTP de erro",
     *         enum={400, 401, 403, 404, 422, 500, 502, 504}
     *     ),
     *     @OA\Property(
     *         property="error",
     *         type="object",
     *         @OA\Property(
     *             property="message",
     *             type="string",
     *             description="Mensagem de erro",
     *             example="Not found."
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Schema(
     *     schema="ErrorsResponse",
     *     type="object",
     *     title="Errors Response",
     *     @OA\Property(
     *         property="success",
     *         type="boolean",
     *         example=false
     *     ),
     *     @OA\Property(
     *         property="code",
     *         type="integer",
     *         example=404
     *     ),
     *     @OA\Property(
     *         property="errors",
     *         type="object",
     *         @OA\AdditionalProperties(
     *             type="array",
     *             @OA\Items(type="string", example="The email has already been taken.")
     *         )
     *     ),
     * )
     */
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
