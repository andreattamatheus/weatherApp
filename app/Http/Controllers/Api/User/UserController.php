<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetUserLocationsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getUserLocations(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            $user = User::query()->with('locations')->findOrFail(auth()->id());
            $locations = $user->locations()->get();

            return response()->json([
                'status' => true,
                'message' => 'User locations fetched successfully',
                'data' => $locations,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching user locations: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error fetching user locations',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
