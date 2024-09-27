<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getUserLocations(Request $request)
    {
        try {
            $user = $request->user();
            $locations = $user->locations()->with('forecasts')->get();

            return response()->json($locations, Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching user locations: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while fetching user locations.',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
