<?php

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Service\Auth\V1\Contracts\AuthServiceInterface;
use App\Traits\SuccessResponsesTrait;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use SuccessResponsesTrait;

    public function __construct(
        private AuthServiceInterface $authService
    ){}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $logoutResource = $this->authService->revokeToken($request);

        return $this->successResponse($logoutResource, 'User logged out successfully');
    }
}
