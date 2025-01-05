<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'is_admin' => $this->is_admin,
            'nursery_id' => $this->nursery_id,
            'nursery' => new NurseryResource($this->whenLoaded('nursery')),
        ];
    }
}
