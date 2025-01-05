<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllergyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pivot' => $this->when($this->pivot, [
                'notes' => $this->pivot->notes,
                'severity' => $this->pivot->severity,
                'symptoms' => $this->pivot->symptoms,
                'treatment' => $this->pivot->treatment,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
