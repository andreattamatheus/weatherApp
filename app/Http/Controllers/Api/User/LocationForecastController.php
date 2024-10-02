<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Services\LocationForecastService;
use App\Services\WeatherApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function __construct(
        private readonly WeatherApiService $weatherService,
        private readonly LocationForecastService $locationForecastService
    ) {}

    public function get(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $city = $request->get('city');
            $state = $request->get('state');

            $response = $this->weatherService->getWeatherForecast($city, $state);
            if (!$response) {
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->locationForecastService->getMostRecentForecast($response);

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching forecast: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching forecast',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $city = $request->get('city');
            $state = $request->get('state');
            $user = auth()->id();

            $weatherResponse = $this->weatherService->getWeatherForecast($city, $state);
            $location = $this->locationForecastService->createLocation($user, $city, $state);
            $this->locationForecastService->createLocationForecast($location, $weatherResponse);

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error saving location: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a weather location.
     *
     * @return JsonResponse|null
     */
    public function destroy(string $locationId): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->locationForecastService->deleteLocation($locationId, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error deleting location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
