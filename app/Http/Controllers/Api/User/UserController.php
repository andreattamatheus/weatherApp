<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getUserLocations(): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = User::query()->with('locations')->findOrFail(auth()->id());
            $locations = $user->locations()->with(['forecasts' => function ($query) {
                $query->select('id', 'location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon');
            }])->get(['id', 'city', 'state', 'created_at']);

            $data = $locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'city' => $location->city,
                    'forecasts' => $location->forecasts->map(function ($forecast) {
                        return [
                            'date' => Carbon::parse($forecast->date)->format('Y-d-m'),
                            'min_temperature' => $forecast->min_temperature,
                            'max_temperature' => $forecast->max_temperature,
                            'condition' => $forecast->condition,
                            'icon' => $forecast->icon,
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'User locations fetched successfully',
                'data' => $data,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching user locations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user locations',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
