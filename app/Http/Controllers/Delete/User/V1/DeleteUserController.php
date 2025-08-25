<?php

namespace App\Http\Controllers\Delete\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteUserController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(int $userId): Response
    {
        try {
            $user = $this->userService->deleteUser($userId)->toArray();

            return $this->successResponse($user, 'User deleted successfully');
        } catch (HttpException $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Error on user delete', $e->getCode());
        } catch (Exception $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
