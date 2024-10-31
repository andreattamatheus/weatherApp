<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationGetRequest;
use App\Http\Resources\MostRecentForecastResource;
use App\Services\LocationForecastService;
use App\Services\WeatherApiService;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function get(LocationGetRequest $request, WeatherApiService $weatherApiService,  LocationForecastService $locationForecastService): mixed
    {
        try {
            $city = $request->get('city');
            $state = $request->get('state');

            $data = $locationForecastService->getMostRecentForecast($city, $state, $weatherApiService);

            return MostRecentForecastResource::make($data);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error get locations: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error fetching most recent forecast',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
