<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Services\LocationForecastService;
use App\Services\WeatherApiService;
use App\Services\WeatherMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function __construct(
        private readonly WeatherMapper $weatherMapper,
        private readonly WeatherApiService $weatherApiService,
        private readonly LocationForecastService $locationForecastService
    ) {}

    public function get(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $city = $request->get('city');
            $state = $request->get('state');

            $response = $this->weatherApiService->getWeatherForecast($city, $state);
            if (!$response) {
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->weatherMapper->getMostRecentForecast($response);

            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error fetching forecast',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $user = $request->user();
            $weatherData = $request->get('weatherData');
            $city = $weatherData['city'];
            $state = $weatherData['state'];

            $location = $this->locationForecastService->createLocation($user, $city, $state);
            $this->locationForecastService->createLocationForecast($location, $weatherData);

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error saving location',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a weather location.
     *
     * @return JsonResponse|null
     */
    public function destroy(Request $request, string $locationId, string $date): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = $request->user();
            $this->locationForecastService->deleteLocation($locationId, $date, $user);

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error deleting location',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
