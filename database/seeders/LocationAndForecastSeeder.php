<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationAndForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::query()->findOrFail(1);
        $user2 = User::query()->findOrFail(2);
        $location1 = Location::query()->create([
            'user_id' => $user1->id,
            'city' => 'New York',
            'state' => 'NY',
        ]);

        $location2 = Location::query()->create([
            'user_id' => $user1->id,
            'city' => 'Los Angeles',
            'state' => 'CA',
        ]);

        // Create locations for User 2
        $location3 = Location::query()->create([
            'user_id' => $user2->id,
            'city' => 'Miami',
            'state' => 'FL',
        ]);

        // Create forecasts for Location 1
        LocationForecast::query()->create([
            'location_id' => $location1->id,
            'date' => now()->format('Y-m-d'),
            'min_temperature' => 18,
            'max_temperature' => 28,
            'condition' => 'Clear sky',
        ]);

        LocationForecast::query()->create([
            'location_id' => $location1->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'min_temperature' => 20,
            'max_temperature' => 30,
            'condition' => 'Sunny',
        ]);

        // Create forecasts for Location 2
        LocationForecast::query()->create([
            'location_id' => $location2->id,
            'date' => now()->format('Y-m-d'),
            'min_temperature' => 22,
            'max_temperature' => 32,
            'condition' => 'Cloudy',
        ]);

        LocationForecast::query()->create([
            'location_id' => $location2->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'min_temperature' => 19,
            'max_temperature' => 29,
            'condition' => 'Rainy',
        ]);

        // Create forecasts for Location 3 (User 2)
        LocationForecast::query()->create([
            'location_id' => $location3->id,
            'date' => now()->format('Y-m-d'),
            'min_temperature' => 25,
            'max_temperature' => 35,
            'condition' => 'Sunny',
        ]);

        LocationForecast::query()->create([
            'location_id' => $location3->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'min_temperature' => 24,
            'max_temperature' => 33,
            'condition' => 'Partly cloudy',
        ]);
    }
}
