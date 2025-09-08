<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\UseCases\Auth\DTOs\LoginUserDto;
use App\UseCases\Auth\LoginUserUseCase;
use App\UseCases\Auth\LogoutUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Authenticate user and return token",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(LoginUserRequest $request, LoginUserUseCase $loginUserUseCase): JsonResponse
    {
        $dto = new LoginUserDto(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        $token = $loginUserUseCase->handle($dto);

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout user (revoke token)",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=204,
     *         description="Logged out successfully"
     *     )
     * )
     */
    public function logout(LogoutUserUseCase $logoutUserUseCase): Response
    {
        $logoutUserUseCase->handle(Auth::user());

        return response()->noContent();
    }
}
