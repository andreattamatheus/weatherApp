<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationForecastService
{
    public function store(Request $request)
    {
        $location = $this->createLocation($request);
        $this->createLocationForecast($location, $request);
    }

    public function createLocation(Request $request): Location
    {
        try {
            $userId = $request->user()->id;
            $city = $request->get('city');
            $state = $request->get('state');

            return DB::transaction(function () use ($userId, $city, $state) {
                return Location::query()->updateOrCreate([
                    'user_id' => $userId,
                    'city' => $city,
                    'state' => $state,
                ]);
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while creating the location.');
        }
    }

    public function createLocationForecast(Location $location, Request $request): LocationForecast
    {
        try {
            $weatherData = $request->get('weatherData');

            return DB::transaction(static function () use ($weatherData, $location) {
                return LocationForecast::query()->updateOrCreate(
                    ['location_id' => $location->id],
                    [
                        'date' => Carbon::parse($weatherData['date'])->format('Y-d-m'),
                        'min_temperature' => $weatherData['min_temperature'],
                        'max_temperature' => $weatherData['max_temperature'],
                        'condition' => $weatherData['condition'],
                        'icon' => $weatherData['icon'],
                    ]
                );
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while creating the forecast.');
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteLocation(string $locationId, string $date, User $user): bool
    {
        try {
            $dateParsed = Carbon::parse($date)->format('Y-d-m');

            return DB::transaction(static function () use ($locationId, $dateParsed, $user) {
                $location = $user->locations()->findOrFail($locationId);

                return $location->forecasts()->where('date', $dateParsed)->delete();
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException('An error occurred while deleting the location.');
        }
    }

    public function getMostRecentForecast($request): array
    {
        try {
            $weatherApiService = new WeatherApiService;
            $weatherData = $weatherApiService->getWeatherForecast($request);
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
            throw new LocationForecastException('An error occurred while deleting the location.');
        }
    }
}
