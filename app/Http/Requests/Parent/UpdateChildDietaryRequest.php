<?php

namespace App\Http\Requests\Parent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildDietaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dietary_requirements' => ['required', 'array'],
            'dietary_requirements.*.id' => ['required', 'exists:dietary_requirements,id'],
            'dietary_requirements.*.notes' => ['nullable', 'string'],
        ];
    }
}
