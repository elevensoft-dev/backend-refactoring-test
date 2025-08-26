<?php

namespace App\Http\Controllers\Post\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Symfony\Component\HttpFoundation\Response;

class CreateNewUserController extends Controller
{
    use SuccessResponsesTrait;
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(CreateNewUserRequest $request): Response
    {
        $data = $request->validated();

        $user = $this->userService->storeNewUser($data)->toArray();

        return $this->jsonSuccessResponse(
            $user,
            'User created successfully',
            Response::HTTP_CREATED
        );
    }
}
