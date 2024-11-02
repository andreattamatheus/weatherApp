<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Http\Resources\ForecastResource;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LocationForecastService
{
    /**
     * Creates a new location based on the provided request data.
     */
    public function createLocation(ForecastResource $weatherData, User $user): Location
    {
        try {
            $userId = $user->id;
            $city = $weatherData->city;
            $state = $weatherData->state;

            return DB::transaction(function () use ($userId, $city, $state) {
                return Location::query()->updateOrCreate([
                    'user_id' => $userId,
                    'city' => $city,
                    'state' => $state,
                ]);
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException($th->getMessage());
        }
    }

    /**
     * Creates a location forecast based on the provided location and request data.
     */
    public function createLocationForecast(Location $location, ForecastResource $weatherData): void
    {
        try {
            $weatherData = ForecastResource::make($weatherData);

            DB::transaction(static function () use ($weatherData, $location) {
                LocationForecast::query()->updateOrCreate(
                    ['location_id' => $location->id],
                    [
                        'date' => $weatherData->date,
                        'min_temperature' => $weatherData->min_temperature,
                        'max_temperature' => $weatherData->max_temperature,
                        'condition' => $weatherData->condition,
                    ]
                );
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException($th->getMessage());
        }
    }

    /**
     * Retrieves the most recent weather forecast based on the provided request.
     */
    public function getMostRecentForecast(string $city, string $state, WeatherApiService $weatherApiService): array
    {
        try {
            $weatherData = $weatherApiService->getWeatherForecast($city, $state);

            $mostRecentForecast = $weatherData->list[0];
            $cityData = $weatherData->city;

            return [
                'date' => $mostRecentForecast->dt_txt,
                'min_temperature' => $mostRecentForecast->main->temp_min,
                'max_temperature' => $mostRecentForecast->main->temp_max,
                'condition' => $mostRecentForecast->weather[0]->description,
                'icon' => $mostRecentForecast->weather[0]->icon,
                'city' => $cityData->name,
                'state' => $cityData->country,
            ];
        } catch (\Throwable $th) {
            throw new LocationForecastException($th->getMessage());
        }
    }
}
