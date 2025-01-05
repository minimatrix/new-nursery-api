<?php

namespace App\Http\Requests\Staff\Room;

use Illuminate\Foundation\Http\FormRequest;

class AssignChildrenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'children' => ['required', 'array'],
            'children.*' => ['required', 'exists:children,id'],
        ];
    }
}
