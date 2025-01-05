<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NurseryRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nursery_name' => ['required', 'string', 'max:255'],
            'nursery_email' => ['required', 'email', 'unique:nurseries,email'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
        ];
    }
}
