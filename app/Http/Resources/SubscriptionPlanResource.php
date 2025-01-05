<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'stripe_price_id' => $this->stripe_price_id,
            'price' => $this->price,
            'billing_interval' => $this->billing_interval,
            'description' => $this->description,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'nurseries_count' => $this->when(isset($this->nurseries_count), $this->nurseries_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
