<?php

namespace App\Http\Requests\Parent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChildImmunisationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'immunisations' => ['required', 'array'],
            'immunisations.*.id' => ['required', 'exists:immunisations,id'],
            'immunisations.*.date_given' => ['nullable', 'date'],
            'immunisations.*.date_due' => ['nullable', 'date'],
            'immunisations.*.notes' => ['nullable', 'string'],
        ];
    }
}
