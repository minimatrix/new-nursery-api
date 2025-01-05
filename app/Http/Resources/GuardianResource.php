<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'relationship' => $this->relationship,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_emergency_contact' => $this->is_emergency_contact,
            'can_pickup' => $this->can_pickup,
            'priority' => $this->priority,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
