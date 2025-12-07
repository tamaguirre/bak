<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => $this->faker->word(),
            'start_time'    => $this->faker->time('H:i:s'),
            'end_time'      => $this->faker->time('H:i:s'),
        ];
    }
}
