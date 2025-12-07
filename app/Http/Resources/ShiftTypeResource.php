<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'color'         => $this->color,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
        ];
    }
}
