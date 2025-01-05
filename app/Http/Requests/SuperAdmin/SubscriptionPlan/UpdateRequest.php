<?php

namespace App\Http\Requests\SuperAdmin\SubscriptionPlan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'stripe_price_id' => [
                'sometimes',
                'required',
                'string',
                'unique:subscription_plans,stripe_price_id,' . $this->route('plan')->id
            ],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'billing_interval' => ['sometimes', 'required', 'string', 'in:month,year'],
            'description' => ['nullable', 'string'],
            'features' => ['sometimes', 'required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
