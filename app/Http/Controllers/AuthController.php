<?php

namespace App\Http\Controllers;

use App\Core\Repositories\IAuthRepository;
use App\Http\Request\LoginAuthRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Autenticação", description="Endpoints de login/logout/me")
 */
class AuthController extends Controller
{
    public function __construct(private IAuthRepository $authRepository) {}

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Login do usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="expires_in", type="string"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginAuthRequest $request): JsonResponse
    {
        return response()->json($this->authRepository->login($request));
    }
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout do usuário",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authRepository->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
   /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Informações do usuário logado",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuário logado",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
