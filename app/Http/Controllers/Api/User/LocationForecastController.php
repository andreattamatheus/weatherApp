<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationDestroyRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Services\WeatherApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    protected WeatherApiService $weatherService;

    public function __construct(WeatherApiService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $city = $request->city;
            $state = $request->state;
            $user = auth()->user();  // Assumes user is authenticated

            DB::transaction(static function () use ($state, $city, $user) {
                $location = Location::query()->create([
                    'user_id' => $user->id,
                    'city' => $city,
                    'state' => $state,
                ]);

                $weatherService = app(WeatherApiService::class);
                $weatherResponse = $weatherService->getWeatherForecast($city, $state);

                if (!$weatherResponse['status']) {
                    throw new \Exception('Error fetching weather data: ' . $weatherResponse['error']['message'] ?? 'Unknown error');
                }

                $weatherData = $weatherResponse['data'];
                LocationForecast::query()->create([
                    'location_id' => $location->id,
                    'date' => date('Y-m-d', $weatherData['dt']),
                    'min_temperature' => $weatherData['main']['temp_min'],
                    'max_temperature' => $weatherData['main']['temp_max'],
                    'condition' => $weatherData['weather'][0]['description'],
                ]);
            });

            return response()->json(['message' => 'Location saved successfully!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the weather data.'], 500);
        }
    }

    /**
     * Delete a weather location.
     *
     * @param LocationDestroyRequest $request
     * @return JsonResponse|null
     */
    public function destroy(LocationDestroyRequest $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            DB::transaction(static function () use ($request) {
                $location = Location::query()->where('user_id', $request->user_id )->findOrFail($request->location_id);
                $location->delete();
            });

            return response()->json(['message' => 'Location deleted successfully!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the location.'], 500);
        }
    }
}
