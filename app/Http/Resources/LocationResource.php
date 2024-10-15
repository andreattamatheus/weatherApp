<?php

namespace App\Http\Resources;

use App\Services\WeatherMapper;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $weatherMapper = new WeatherMapper;

        return $weatherMapper->mapLocation([
            'id' => $this->id,
            'city' => $this->city,
            'forecasts' => $this->forecasts,
        ]);
    }
}
