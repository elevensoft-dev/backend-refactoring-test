<?php

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\V1\LoginRequest;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Post(
 *     path="/v1/auth/login",
 *     summary="Login do usuário",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", example="user@email.com"),
 *             @OA\Property(property="password", type="string", example="123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login bem-sucedido",
 *         @OA\JsonContent(ref="#/components/schemas/LoginResource")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Credenciais inválidas",
 *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 *     )
 * )
 */
class LoginController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private AuthServiceInterface $authService
    ){}

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $loginData = $this->authService->getAccessToken($credentials);

        return $this->successResponse($loginData, 'User logged in successfully');
    }
}
