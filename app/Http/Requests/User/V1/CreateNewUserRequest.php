<?php

namespace App\Http\Requests\User\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateNewUserRequest",
 *     type="object",
 *     title="Create User Validation",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Test user"),
 *     @OA\Property(property="email", type="string", example="test@email.com"),
 *     @OA\Property(property="password", type="string", format="password", example="123456"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", example="123456")
 * )
 */
class CreateNewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}
