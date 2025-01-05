<?php

namespace App\Http\Requests\Staff\Room;

use Illuminate\Foundation\Http\FormRequest;

class AssignStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'staff' => ['required', 'array'],
            'staff.*.user_id' => ['required', 'exists:users,id'],
            'staff.*.is_room_leader' => ['required', 'boolean'],
        ];
    }
}
