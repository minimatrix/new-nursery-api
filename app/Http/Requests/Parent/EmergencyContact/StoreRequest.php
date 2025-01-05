<?php

namespace App\Http\Requests\Parent\EmergencyContact;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'alternative_phone' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', 'integer', 'min:1'],
        ];
    }
}
