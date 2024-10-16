<?php

namespace App\Services;

use App\Exceptions\LocationForecastException;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastService
{
    public function createLocationForecast(Location $location, array $weatherData): LocationForecast
    {
        try {
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
            \Log::error('Error creating location: ' . $th->getMessage());
            throw new LocationForecastException($th->getMessage());
        }
    }

    public function createLocation(string $userId, string $city, string $state): Location
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
    public function deleteLocation(string $locationId, string $date, User $user): JsonResponse
    {
        try {
            $dateParsed = Carbon::parse($date)->format('Y-d-m');
            DB::transaction(static function () use ($locationId, $dateParsed, $user) {
                $location = $user->locations()->findOrFail($locationId);
                $location->forecasts()->where('date', $dateParsed)->delete();
            });
            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            \Log::error('Error deleting location: ' . $th->getMessage());
            throw new LocationForecastException('An error occurred while deleting the location.');
        }
    }
}
