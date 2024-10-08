<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getUserLocations(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = $request->user()->query()->with('locations')->first();
            $locations = $user->locations()->with(['forecasts' => function ($query) {
                $query->select('id', 'location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon');
            }])->get(['id', 'city', 'state', 'created_at']);

            return response()->json([
                'success' => true,
                'message' => 'User locations fetched successfully',
                'data' => LocationResource::collection($locations),
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
