<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Account\PasswordResetRequest;
use App\Http\Requests\Account\SendResetLinkRequest;
use App\UseCases\Account\DTOs\ResetPasswordDto;
use App\UseCases\Account\DTOs\SendResetLinkDto;
use App\UseCases\Account\ResetPasswordUseCase;
use App\UseCases\Account\SendResetLinkUseCase;
use Illuminate\Http\Response;

final class ForgotPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/account/forgot-password",
     *     summary="Send password reset link",
     *     tags={"Account"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email"},
     *
     *             @OA\Property(property="email", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Reset link sent"
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function sendResetLinkEmail(SendResetLinkRequest $request, SendResetLinkUseCase $sendResetLinkUseCase): Response
    {
        $dto = new SendResetLinkDto(email: $request->validated('email'));

        $sendResetLinkUseCase->handle($dto);

        return response()->noContent(200);
    }

    /**
     * @OA\Post(
     *     path="/account/reset-password",
     *     summary="Reset password",
     *     tags={"Account"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"email", "token", "password"},
     *
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully"
     *     ),
     *     @OA\Response(response=400, description="Invalid token or email")
     * )
     */
    public function reset(PasswordResetRequest $request, ResetPasswordUseCase $resetPasswordUseCase): Response
    {
        $dto = new ResetPasswordDto(
            email: $request->validated('email'),
            token: $request->validated('token'),
            password: $request->validated('password'),
        );

        $resetPasswordUseCase->handle($dto);

        return response()->noContent(200);
    }
}
