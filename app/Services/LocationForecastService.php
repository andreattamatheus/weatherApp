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
     *
     * @param \Illuminate\Http\Request $request The request instance containing the data to be stored.
     * @return \Illuminate\Http\Response The response instance indicating the result of the store operation.
     */
    public function store(Request $request)
    {
        $location = $this->createLocation($request);
        $this->createLocationForecast($location, $request);
    }

    /**
     * Creates a new location based on the provided request data.
     *
     * @param Request $request The request object containing location data.
     * @return Location The newly created location instance.
     */
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
            throw new LocationForecastException($th->getMessage());
        }
    }

    /**
     * Creates a location forecast based on the provided location and request data.
     *
     * @param Location $location The location for which the forecast is to be created.
     * @param Request $request The request containing the forecast data.
     * @return void
     */
    public function createLocationForecast(Location $location, Request $request): void
    {
        try {
            $weatherData = ForecastResource::make($request);

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
     * @param string $locationId The ID of the location to be deleted.
     * @param string $date The date for which the location forecast should be deleted.
     * @param User $user The user requesting the deletion.
     * @return void
     */
    public function deleteLocation(string $locationId, string $date, User $user): void
    {
        try {
            $dateParsed = Carbon::parse($date)->format('Y-d-m');

            DB::transaction(static function () use ($locationId, $dateParsed, $user) {
                $location = $user->locations()->findOrFail($locationId);

                $location->forecasts()->where('date', $dateParsed)->delete();
            });
        } catch (\Throwable $th) {
            throw new LocationForecastException($th->getMessage());
        }
    }

    /**
     * Retrieves the most recent weather forecast based on the provided request.
     *
     * @param \Illuminate\Http\Request $request The request object containing necessary parameters.
     * @return array An array containing the most recent forecast data.
     */
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
            throw new LocationForecastException($th->getMessage());
        }
    }
}
