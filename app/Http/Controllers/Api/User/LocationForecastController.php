<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationGetRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Services\LocationForecastService;
use App\Services\WeatherMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function __construct(
        private readonly WeatherMapper $weatherMapper,
        private readonly LocationForecastService $locationForecastService
    ) {}

    public function get(LocationGetRequest $request): ?JsonResponse
    {
        try {
            $data = $this->weatherMapper->getMostRecentForecast($request);

            return response()->json([
                'success' => true,
                'data' => $data,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(LocationStoreRequest $request): ?JsonResponse
    {
        try {
            $this->locationForecastService->store($request);

            return response()->json([
                'success' => true,
                'message' => 'Location saved successfully!',
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
     */
    public function destroy(Request $request, string $locationId, string $date): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = $request->user();
            $this->locationForecastService->deleteLocation($locationId, $date, $user);

            return response()->json([
                'success' => true,
                'message' => 'Location deleted successfully!',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error deleting location',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
