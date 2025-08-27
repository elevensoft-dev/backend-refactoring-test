<?php

namespace App\Http\Controllers\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateNewUserController extends Controller
{
    use SuccessResponsesTrait;
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

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
