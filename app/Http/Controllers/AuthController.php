<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Register a new user
     *
     * @OA\Post(
     *      path="/register",
     *      operationId="registerUser",
     *      summary="Register a new user",
     *      tags={"Authentication"},
     *      description="Register a new user and return access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/User"),
     *              @OA\Property(property="token", type="string", example="1|abc123...")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        
        return response()->json([
            'user' => UserResource::make($result['user']),
            'token' => $result['token']
        ], 201);
    }

    /**
     * Login user
     *
     * @OA\Post(
     *      path="/login",
     *      operationId="loginUser",
     *      summary="Login user",
     *      tags={"Authentication"},
     *      description="Login user and return access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/User"),
     *              @OA\Property(property="token", type="string", example="1|abc123...")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials"
     *      )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());
        
        return response()->json([
            'user' => UserResource::make($result['user']),
            'token' => $result['token']
        ]);
    }

    /**
     * Logout user
     *
     * @OA\Post(
     *      path="/logout",
     *      operationId="logoutUser",
     *      summary="Logout user",
     *      tags={"Authentication"},
     *      description="Logout user and revoke access token",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Logout successful",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Logged out successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => trans('messages.logged_out')
        ]);
    }
}
