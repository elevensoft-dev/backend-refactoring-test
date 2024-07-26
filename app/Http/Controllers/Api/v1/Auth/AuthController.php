<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\AuthFormRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Make a login
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/v1/auth/login",
     *      operationId="Login",
     *      summary="Autehticate with valid user",
     *      tags={"Auth"},
     *      description="Make a Login",
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="Provide All Info Below",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="email", format="text", example="example@elevensoft.dev"),
     *       @OA\Property(property="password", type="string", format="text", example="password"),
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function login(AuthFormRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            $data = [
                'message' => 'Login efetuado com sucesso',
                'user' => Auth::user(),
                'token' => $token
            ];
            return response()->json($data, JsonResponse::HTTP_OK);
        } else {
            return response()->json(['error' => 'Unauthorised'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Make a login
     *
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/v1/auth/me",
     *      operationId="Get User logged data",
     *      summary="Return infos of user logged",
     *      tags={"Auth"},
     *      description="Get User logged data",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function me(): JsonResponse
    {
        return response()->json(['user' => Auth::user()], JsonResponse::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['Logout efetuado com sucesso'], JsonResponse::HTTP_OK);
    }
}
