<?php

namespace App\Http\Requests\Staff\PricingBand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'min_age_months' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
                'max:240',
                'lt:max_age_months'
            ],
            'max_age_months' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:240',
                'gt:min_age_months'
            ],
            'hourly_rate' => ['sometimes', 'required', 'numeric', 'min:0'],
            'grant_rate' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->has(['min_age_months', 'max_age_months'])) {
                    // Check for overlapping age ranges in the same nursery
                    $overlapping = \App\Models\PricingBand::where('nursery_id', auth()->user()->nursery_id)
                        ->where('id', '!=', $this->route('pricing_band')->id)
                        ->where(function ($query) {
                            $query->whereBetween('min_age_months', [$this->min_age_months, $this->max_age_months])
                                ->orWhereBetween('max_age_months', [$this->min_age_months, $this->max_age_months]);
                        })->exists();

                    if ($overlapping) {
                        $validator->errors()->add(
                            'age_range',
                            'The age range overlaps with an existing pricing band'
                        );
                    }
                }
            }
        ];
    }
}
