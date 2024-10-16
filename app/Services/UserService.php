<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\LocationResource;


class UserService
{
    public function getUserLocations(User $user): mixed
    {
        $user = $user->query()->with('locations')->first();

        $locations = $user->locations()->with(['forecasts' => function ($query) {
            $query->select('id', 'location_id', 'date', 'min_temperature', 'max_temperature', 'condition', 'icon');
        }])->get(['id', 'city', 'state', 'created_at']);

        return LocationResource::collection($locations);
    }
}
