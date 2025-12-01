<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_id'       => Room::factory()->create()->id,
            'start_date'    => $this->faker->dateTime(),
            'end_date'      => $this->faker->dateTime(),
            'emergency'     => $this->faker->boolean(),
            'doctor_id'     => User::factory()->create()->id,
            'patient_name'  => $this->faker->name(),
            'notes'         => $this->faker->sentence(),
            'restroom'      => $this->faker->boolean(),
        ];
    }
}
