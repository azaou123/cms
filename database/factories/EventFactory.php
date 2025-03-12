<?php

namespace Database\Factories;

use App\Models\Cell;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 5) . ' days');

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->faker->address,
            'type' => $this->faker->randomElement(['workshop', 'seminar', 'conference', 'meeting', 'other']),
            'cell_id' => Cell::factory(),
        ];
    }
}