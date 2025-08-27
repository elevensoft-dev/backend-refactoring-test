<?php

namespace App\Exceptions;

use App\Traits\ErrorResponsesTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class LogoutErrorException extends Exception
{
    use ErrorResponsesTrait;

    protected $message = "Unxpected error during logout.";

    protected $status = Response::BAD_REQUEST;

    public function render(): JsonResponse
    {
        return $this->errorResponse($this->message, $this->status);
    }
}
