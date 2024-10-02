<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Models\Location;
use App\Models\LocationForecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LocationForecastService
{
    public function createLocationForecast(Location $location, array $weatherData)
    {
        try {
            return DB::transaction(static function () use ($weatherData, $location) {
                LocationForecast::query()->updateOrCreate(
                    ['location_id' => $location->id],
                    [
                        'date' => Carbon::parse($weatherData['date'])->format('Y-d-m'),
                        'min_temperature' => $weatherData['min_temperature'],
                        'max_temperature' => $weatherData['max_temperature'],
                        'condition' => $weatherData['condition'],
                        'icon' => $weatherData['icon']
                    ]
                );
            });
        } catch (\Throwable $th) {
            \Log::error('Error creating location: ' . $th->getMessage());
            throw new LocationForecastException($th->getMessage());
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
            \Log::error('Error creating location: ' . $th->getMessage());
            throw new LocationForecastException('An error occurred while saving the location.');
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteLocation(int $locationId, string $date): void
    {
        try {
            $dateParsed = Carbon::parse($date)->format('Y-d-m');
            DB::transaction(static function () use ($locationId, $dateParsed) {
                $location = auth()->user()->locations()->findOrFail($locationId);
                $location->forecasts()->where('date', $dateParsed)->delete();
            });
        } catch (\Throwable $th) {
            \Log::error('Error deleting location: ' . $th->getMessage());
            throw new LocationForecastException('An error occurred while deleting the location.');
        }
    }

    public function getMostRecentForecast(array $response): array
    {
        $mostRecentForecast = $response['data']['list'][count($response['data']['list']) - 1];
        $cityData = $response['data']['city'];

        return [
            'date' => Carbon::parse($mostRecentForecast['dt'])->format('Y-d-m'),
            'min_temperature' => $mostRecentForecast['main']['temp_min'],
            'max_temperature' => $mostRecentForecast['main']['temp_max'],
            'condition' => $mostRecentForecast['weather'][0]['description'],
            'icon' => $mostRecentForecast['weather'][0]['icon'],
            'city' => $cityData['name'],
            'state' => $cityData['country'],
        ];
    }
}
