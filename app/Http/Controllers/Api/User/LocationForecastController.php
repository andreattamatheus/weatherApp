<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationGetRequest;
use App\Http\Resources\MostRecentForecastResource;
use App\Services\LocationForecastService;
use Symfony\Component\HttpFoundation\Response;

class LocationForecastController extends Controller
{
    public function __construct(
        private readonly LocationForecastService $locationForecastService
    ) {}

    public function get(LocationGetRequest $request)
    {
        try {
            $data = $this->locationForecastService->getMostRecentForecast($request);

            return MostRecentForecastResource::make($data);
        } catch (\Exception $e) {
            logger()->channel('daily')->error('Error get locations: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error fetching most recent forecast',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
