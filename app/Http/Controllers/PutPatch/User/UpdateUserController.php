<?php

namespace App\Http\Controllers\PutPatch\User;

use App\Http\Controllers\Controller;
use App\Service\User\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;
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

    public function __invoke($id, Request $request): Response
    {
        try {
            $data = $request->only(['name', 'email', 'password']);

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
