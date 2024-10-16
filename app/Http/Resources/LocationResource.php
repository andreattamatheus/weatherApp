<?php

namespace App\Http\Resources;

use App\Services\WeatherMapper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public int $id;
    public string $city;
    public Collection $forecasts;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->id = $resource->id;
        $this->city = $resource->city;
        $this->forecasts = $resource->forecasts;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $weatherMapper = new WeatherMapper;

        return $weatherMapper->mapLocation([
            'id' => $this->id,
            'city' => $this->city,
            'forecasts' => $this->forecasts,
        ]);
    }
}
