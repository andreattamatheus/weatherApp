<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Http\Resources\ForecastResource;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationForecastService
{
    /**
     * Store the location forecast data.
     */
    public function store(array $weatherData, User $user): void
    {
        $weatherData = ForecastResource::make($weatherData);
        $location = $this->createLocation($weatherData, $user);
        $this->createLocationForecast($location, $weatherData);
    }

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
     * Deletes a location forecast for a specific date and user.
     *
     * @param  string  $locationId  The ID of the location to be deleted.
     * @param  string  $date  The date for which the location forecast should be deleted.
     * @param  User  $user  The user requesting the deletion.
     */
    public function deleteLocation(string $locationId, string $date, User $user): void
    {
        try {
            $location = $user->locations()->findOrFail($locationId);
            $forecast = $location->forecasts()->where('date', $date)->first();

            DB::transaction(static function () use ($forecast) {
                $forecast->delete();
            });
        } catch (\Throwable $th) {
            logger()->channel('daily')->error('Error destroy user locations: ' . $th->getMessage());

            throw new LocationForecastException('Error deleting location forecast data.');
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
