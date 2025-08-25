<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'search' => ['sometimes', 'string'],
            'trashed' => ['sometimes', 'string', 'in:true,1'],
        ];
    }
}
