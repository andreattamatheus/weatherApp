<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationDestroyRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Services\LocationForecastService;
use App\Services\WeatherApiService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function __construct(
        private readonly WeatherApiService $weatherService,
        private readonly LocationForecastService $locationForecastService
    )
    {
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $city = $request->get('city');
            $state = $request->get('state');
            $user = $request->get('userId');

            $weatherResponse = $this->weatherService->getWeatherForecast($city, $state);
            $location = $this->locationForecastService->createLocation($user, $city, $state);
            $this->locationForecastService->createLocationForecast($location, $weatherResponse);

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a weather location.
     *
     * @param LocationDestroyRequest $request
     * @return JsonResponse|null
     */
    public function destroy(LocationDestroyRequest $request, string $locationId): ?\Illuminate\Http\JsonResponse
    {
        try {
            $this->locationForecastService->deleteLocation($locationId, $request->get('userId'));

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
