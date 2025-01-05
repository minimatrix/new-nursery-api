<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImmunisationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'requires_dates' => $this->requires_dates,
            'pivot' => $this->when($this->pivot, [
                'date_given' => $this->pivot->date_given,
                'date_due' => $this->pivot->date_due,
                'notes' => $this->pivot->notes,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
