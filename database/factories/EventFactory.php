<?php

namespace Database\Factories;

use App\Models\EventType;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_type_id'     => EventType::factory()->create()->id,
            'reservation_id'    => Reservation::factory()->create()->id,
        ];
    }
}
