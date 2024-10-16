<?php

namespace App\Services;

use App\Models\User;
use Exception;

class UserService
{
    public function getUserLocations(User $user): mixed
    {
        try {
            $user = $user->query()->with('locations')->first();

            return $user->locations()->with(['forecasts' => function ($query) {
                $query->select('id', 'location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon');
            }])->get(['id', 'city', 'state', 'created_at']);
        } catch (\Throwable $th) {
            \Log::error('Error getting user locations: '.$th->getMessage());
            throw new Exception('An error occurred while getting user locations.');
        }
    }
}
