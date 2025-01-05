<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildAllergyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'allergies' => ['required', 'array'],
            'allergies.*.id' => ['required', 'exists:allergies,id'],
            'allergies.*.notes' => ['nullable', 'string'],
            'allergies.*.severity' => ['required', 'in:mild,moderate,severe'],
            'allergies.*.symptoms' => ['nullable', 'string'],
            'allergies.*.treatment' => ['nullable', 'string'],
        ];
    }
}
