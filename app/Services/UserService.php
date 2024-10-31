<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;

class UserService
{
    public function getUserLocations(Request $request): mixed
    {
        try {
            $user = $request->user()->query()->with('locations')->first();

            return $user->locations()->with(['forecasts' => function ($query) {
                $query->select('id', 'location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon');
            }])->paginate($request->get('per_page', 10));
        } catch (\Throwable $th) {
            logger()->channel('daily')->error('Error getUserLocations locations: ' . $th->getMessage());

            throw new Exception('An error occurred while getting user locations.');
        }
    }
}
