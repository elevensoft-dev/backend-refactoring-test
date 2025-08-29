<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\UpdateUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;

/**
 * Update user
 *
 * @OA\Put(
 *     path="/v1/users/update/{id}",
 *     summary="Update an user",
 *     tags={"Users"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Teste Nome Atualizado"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User update successfully",
 *         @OA\JsonContent(ref="#/components/schemas/UpdateUserSuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Not authorized",
 *         @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/UserNotFoundResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/UpdateUserUnprocessableEntityResponse")
 *     )
 * )
 */
class UpdateUserController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function __invoke(int $id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->updateUser($data, $id);

        return $this->successResponse($user, 'User updated successfully');
    }
}
