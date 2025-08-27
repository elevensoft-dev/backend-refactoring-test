<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionsResponseTrait
{
    use ErrorResponsesTrait;

    private function exceptionResponse(Exception $exception, Request $request): JsonResponse
    {
        if (! $request->expectsJson()) {
            return $this->errorResponse(
                'Content not valid',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        if ($exception instanceof ValidationException) {
            return $this->errorsResponse(
                $exception->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $errorResponse = [
            'message' => 'Unexpected error.',
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ];

        if ($exception instanceof InvalidArgumentException) {
            $errorResponse = [
                'message' => 'Logic error.',
                'code' => Response::HTTP_BAD_REQUEST,
            ];
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $errorResponse = [
                'message' => 'Method not allowed.',
                'code' => Response::HTTP_METHOD_NOT_ALLOWED,
            ];
        }

        if ($exception instanceof NotFoundHttpException) {
            $errorResponse = [
                'message' => 'Url not found.',
                'code' => Response::HTTP_NOT_FOUND,
            ];
        }

        if ($exception instanceof HttpExceptionInterface) {
            $errorResponse = [
                'message' => 'HTTP Error',
                'code' => $exception->getStatusCode(),
            ];
        }

        Log::channel('exceptions')->error($exception->getMessage(), [
            'exception' => get_class($exception),
            'trace' => $exception->getTraceAsString(),
        ]);

        return $this->errorResponse(
            $errorResponse['message'],
            $errorResponse['code'],
        );
    }
}
