<?php

namespace Database\Factories;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationForecast>
 */
class LocationForecastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location_id' => Location::factory()->create()->id,
            'date' => Carbon::now()->format('Y-d-m'),
            'min_temperature' => $this->faker->randomFloat(2, 0, 100),
            'max_temperature' => $this->faker->randomFloat(2, 0, 100),
            'condition' => $this->faker->word,
        ];
    }
}
