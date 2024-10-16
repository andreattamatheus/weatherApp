<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MostRecentForecastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'state' => $this['state'],
            'city' => $this['city'],
            'date' => $this['date'],
            'min_temperature' => $this['min_temperature'],
            'max_temperature' => $this['max_temperature'],
            'condition' => $this['condition'],
            'icon' => $this['icon'],
        ];
    }
}
