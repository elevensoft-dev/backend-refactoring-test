<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|required|min:3|max:255',
            'email' => 'string|required|email|unique:users',
            'password' => 'string|required|min:6|max:255',
            'passwordConfirmation' => 'string|required|min:6|max:255|same:password'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'email.unique' => 'O campo email já está em uso',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres',
            'password.max' => 'O campo senha deve ter no máximo 255 caracteres',
            'passwordConfirmation.required' => 'O campo confirmação de senha é obrigatório',
            'passwordConfirmation.min' => 'O campo confirmação de senha deve ter no mínimo 6 caracteres',
            'passwordConfirmation.max' => 'O campo confirmação de senha deve ter no máximo 255 caracteres',
            'passwordConfirmation.same' => 'O campo confirmação de senha deve ser igual ao campo senha',
        ];
    }
}
