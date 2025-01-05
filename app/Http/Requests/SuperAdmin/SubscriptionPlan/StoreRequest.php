<?php

namespace App\Http\Requests\SuperAdmin\SubscriptionPlan;

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
            'stripe_price_id' => ['required', 'string', 'unique:subscription_plans'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_interval' => ['required', 'string', 'in:month,year'],
            'description' => ['nullable', 'string'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
