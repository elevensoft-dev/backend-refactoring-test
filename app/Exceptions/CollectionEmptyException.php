<?php

namespace App\Exceptions;

use App\Traits\ErrorResponsesTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionEmptyException extends Exception
{
    use ErrorResponsesTrait;

    protected $message = "Empty collection.";

    protected $status = Response::HTTP_NOT_FOUND;

    public function render(): JsonResponse
    {
        return $this->errorResponse($this->message, $this->status);
    }
}
