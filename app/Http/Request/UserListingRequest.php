<?php


namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserListingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'email',
            'name' => 'string',
            'perPage' => 'integer|min:1'
        ];
    }
}
