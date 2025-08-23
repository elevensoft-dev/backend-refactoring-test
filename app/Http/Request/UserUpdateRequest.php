<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|min:3|max:255',
            'password' => 'sometimes|string|min:6|max:255',
            'passwordConfirmation' => 'sometimes|string|min:6|max:255|same:password'
        ];
    }
    public function messages()
    {
        return [
            'name.string' => 'O campo nome deve ser uma string',
            'name.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
            'password.string' => 'O campo senha deve ser uma string',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
            'password.max' => 'O campo senha deve ter no máximo 255 caracteres',
            'passwordConfirmation.string' => 'O campo confirmação de senha deve ser uma string',
            'passwordConfirmation.min' => 'O campo confirmação de senha deve ter no mínimo 6 caracteres',
            'passwordConfirmation.max' => 'O campo confirmação de senha deve ter no máximo 255 caracteres',
            'passwordConfirmation.same' => 'O campo confirmação de senha deve ser igual ao campo senha',
        ];
    }
}
