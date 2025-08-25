<?php

namespace App\Http\Controllers\Post\User\V1;

use App\Http\Controllers\Controller;
use App\Service\User\V1\Contracts\UserServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateNewUserController extends Controller
{
    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $data = $request->all();

            $user = $this->userService->storeNewUser($data)->toArray();

            return $this->successResponse(
                $user,
                'User created successfully',
                Response::HTTP_CREATED
            );
        } catch (HttpException $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Error on user creation', $e->getCode());
        } catch (Exception $e) {
            Log::error($e->__tostring());

            return $this->errorResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
