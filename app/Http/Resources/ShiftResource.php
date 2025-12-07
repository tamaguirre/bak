<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'date'  => $this->date,
            'type'  => new ShiftTypeResource($this->whenLoaded('type')),
            'user'  => new UserResource($this->whenLoaded('user')),
        ];
    }
}
