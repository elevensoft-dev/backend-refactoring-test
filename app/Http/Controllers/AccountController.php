<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Account\ChangePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\UseCases\Account\ChangePasswordUseCase;
use App\UseCases\Account\DTOs\ChangePasswordDto;
use App\UseCases\Account\DTOs\UpdateUserAccountDto;
use App\UseCases\Account\UpdateProfileUseCase;
use App\UseCases\User\DTOs\CreateUserDto;
use App\UseCases\User\RegisterUserUseCase;
use Auth;
use Illuminate\Http\Response;

final class AccountController extends Controller
{
    /**
     * @OA\Post(
     *     path="/account/register",
     *     summary="Register a new user",
     *     tags={"Account"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(StoreUserRequest $request, RegisterUserUseCase $registerUserUseCase): UserResource
    {
        $dto = new CreateUserDto(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
        );

        $user = $registerUserUseCase->handle($dto);

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/account/profile",
     *     summary="Get authenticated user profile",
     *     tags={"Account"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user profile",
     *
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function profile()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @OA\Put(
     *     path="/account/profile",
     *     summary="Update authenticated user profile",
     *     tags={"Account"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User profile updated",
     *
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function update(UpdateProfileRequest $request, UpdateProfileUseCase $updateProfileUseCase): UserResource
    {
        $dto = new UpdateUserAccountDto(
            id: $request->user()->id,
            name: $request->validated('name'),
            email: $request->validated('email'),
        );

        $user = $updateProfileUseCase->handle($dto);

        return new UserResource($user);
    }

    /**
     * @OA\Put(
     *     path="/account/password",
     *     summary="Change authenticated user password",
     *     tags={"Account"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"password"},
     *
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully"
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request, ChangePasswordUseCase $changePasswordUseCase): Response
    {
        $dto = new ChangePasswordDto(
            userId: $request->user()->id,
            password: $request->validated('password'),
        );

        $changePasswordUseCase->handle($dto);

        return response()->noContent(200);
    }
}
