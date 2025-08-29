<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\V1\CreateNewUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create new user
 *
 * @OA\Post(
 *     path="/v1/users/new",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/CreateNewUserRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/CreateNewUserSuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/CreateUserUnprocessableEntityResponse")
 *     )
 * )
 */
class CreateNewUserController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private UserServiceInterface $userService,
    ) {}

    public function __invoke(CreateNewUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->storeNewUser($data);

        return $this->successResponse(
            $user,
            'User created successfully',
            Response::HTTP_CREATED
        );
    }
}
