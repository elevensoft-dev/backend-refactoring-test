<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Não autenticado.'
            ], 401);
        }
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Recurso não encontrado.'
            ], 404);
        }
        if ($e instanceof HttpException) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Erro de requisição.'
            ], $e->getStatusCode());
        }
        if ($e instanceof InvalidUserDataException) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        if ($e instanceof UserNotFoundException) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
        if ($e instanceof UserUpdateFailedException) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }
        return response()->json([
            'message' => 'Erro interno no servidor.',
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
