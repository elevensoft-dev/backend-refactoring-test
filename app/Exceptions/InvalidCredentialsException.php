<?php

namespace App\Exceptions;

use App\Traits\ErrorResponsesTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="InvalidCredentials",
 *     type="object",
 *     title="Login Resource",
 *     @OA\Property(property="token_type", type="string", example="Bearer"),
 *     @OA\Property(property="access_token", type="string", example=""),
 * )
 */
class InvalidCredentialsException extends Exception
{
    use ErrorResponsesTrait;

    protected $message = "Invalid credentials.";

    protected $status = Response::HTTP_UNAUTHORIZED;

    public function render(): JsonResponse
    {
        return $this->errorResponse($this->message, $this->status);
    }
}
