<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'emergency'     => (bool) $this->emergency,
            'room'          => new RoomResource($this->whenLoaded('room')),
            'doctor'        => new UserResource($this->whenLoaded('doctor')),
        ];
    }
}
