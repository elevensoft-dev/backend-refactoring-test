<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Models\User;


class CpfLoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login",
     *     description="Authenticates a user using CPF and password.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"cpf", "password"},
     *                 @OA\Property(property="cpf", type="string", example="12345678900"),
     *                 @OA\Property(property="password", type="string", example="password123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully authenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your_jwt_token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="As credenciais fornecidas são inválidas.")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('cpf', 'password');
 
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('elevensoft')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Credenciais inválidas.'], 401);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout",
     *     description="Revoke the token.",
     *     tags={"Authentication"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout completed successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout completed successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated user.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated user.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $user->tokens()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

     /**
     * @OA\Put(
     *     path="/login/{id}/password",
     *     summary="Atualizar senha do usuário",
     *     description="Altera a senha de um usuário específico.",
     *     tags={"Authentication"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID do usuário"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="novaSenha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha atualizada com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Senha atualizada com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Usuário não encontrado.")
     *         )
     *     )
     * )
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Senha atualizada com sucesso.']);
    }
}