<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class AuthFormRequest extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Errors found',
            'data'      => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'email.required' => 'O Campo Email é obrigatório',
            'email.email' => 'O Campo Email possui um formato invalido',
            'password.required' => 'O Campo senha é obrigatório',
            'password.min' => 'O Campo senha deve conter no mínimo 8 caracteres'
        ];
    }

}
