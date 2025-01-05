<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GuardianResource;
use App\Http\Resources\AllergyResource;
use App\Http\Resources\DietaryRequirementResource;
use App\Http\Resources\ImmunisationResource;

class ChildResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'notes' => $this->notes,
            'guardians' => GuardianResource::collection($this->whenLoaded('guardians')),
            'allergies' => AllergyResource::collection($this->whenLoaded('allergies')),
            'dietary_requirements' => DietaryRequirementResource::collection($this->whenLoaded('dietaryRequirements')),
            'immunisations' => ImmunisationResource::collection($this->whenLoaded('immunisations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
