<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'string|max:255',
            'email'    => 'string|email|max:255|unique:users,email,' . $this->route('user'),
            'password' => 'string|min:8|max:15',
        ];
    }
}
