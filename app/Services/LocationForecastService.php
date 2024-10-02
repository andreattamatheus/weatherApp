<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Models\Location;
use App\Models\LocationForecast;
use Illuminate\Support\Facades\DB;

class LocationForecastService
{
    public function createLocationForecast(Location $location, array $weatherData)
    {
        try {
            if (empty($weatherData['data']['list'])) {
                throw new LocationForecastException('No weather data found for the location.');
            }
            $mostRecentForecast = $weatherData['data']['list'][count($weatherData['data']['list']) - 1];
            return DB::transaction(static function () use ($mostRecentForecast, $location) {
                return LocationForecast::query()->updateOrCreate(
                    ['location_id' => $location->id],
                    [
                        'date' => date('Y-m-d', $mostRecentForecast['dt']),
                        'min_temperature' => $mostRecentForecast['main']['temp_min'],
                        'max_temperature' => $mostRecentForecast['main']['temp_max'],
                        'condition' => $mostRecentForecast['weather'][0]['description'],
                    ]
                );
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while create the location forecast.');
        }
    }

    public function createLocation(string $userId, string $city, string $state): Location|null|\Illuminate\Database\Eloquent\Model
    {
        try {
            return DB::transaction(function () use ($userId, $city, $state) {
                return Location::query()->updateOrCreate([
                    'user_id' => $userId,
                    'city' => $city,
                    'state' => $state,
                ]);
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while saving the location.');
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteLocation($locationId, $userId): void
    {
        try {
            DB::transaction(static function () use ($userId, $locationId) {
                $location = Location::query()->where('user_id', $userId)->findOrFail($locationId);
                $location->delete();
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while deleting the location.');
        }
    }

    public function getMostRecentForecast(array $response): array
    {
        $mostRecentForecast = $response['data']['list'][count($response['data']['list']) - 1];

        return [
            'date' => date('Y-m-d', $mostRecentForecast['dt']),
            'min_temperature' => $mostRecentForecast['main']['temp_min'],
            'max_temperature' => $mostRecentForecast['main']['temp_max'],
            'condition' => $mostRecentForecast['weather'][0]['description']
        ];
    }
}
