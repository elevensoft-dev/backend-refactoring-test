<?php

namespace App\Http\Controllers\Get\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AllUsersController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke()
    {
        try {
            $users = $this->userService->getAllUsers()->toArray();

            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (HttpException $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Error on users retrieve', $e->getCode());
        } catch (Exception $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
