<?php

namespace App\Http\Controllers\PutPatch\User\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateUserController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(int $id, UpdateUserRequest $request): Response
    {
        try {
            $data = $request->validated();

            $user = $this->userService->updateUser($data, $id)->toArray();

            return $this->successResponse($user, 'User updated successfully');
        } catch (HttpException $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Error on user update', $e->getCode());
        } catch (Exception $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
