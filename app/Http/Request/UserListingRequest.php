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
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1'
        ];
    }
}
