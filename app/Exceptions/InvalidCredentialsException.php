<?php

namespace App\Exceptions;

use App\Traits\ErrorResponsesTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends Exception
{
    use ErrorResponsesTrait;

    protected $message = 'Invalid credentials.';

    protected $status = Response::HTTP_UNAUTHORIZED;

    public function render(): JsonResponse
    {
        return $this->errorResponse($this->message, $this->status);
    }
}
