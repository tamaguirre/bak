<?php

namespace Database\Factories;

use App\Models\ShiftType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => User::factory()->create()->id,
            'shift_type_id' => ShiftType::factory()->create()->id,
            'date'          => $this->faker->date(),
        ];
    }
}
