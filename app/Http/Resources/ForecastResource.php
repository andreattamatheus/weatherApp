<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ForecastResource extends JsonResource
{
    public string $date;

    public string $min_temperature;

    public string $max_temperature;

    public string $condition;


    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->date = $resource->date;
        $this->min_temperature = $resource->min_temperature;
        $this->max_temperature = $resource->max_temperature;
        $this->condition = $resource->condition;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'date' => Carbon::parse($this->date)->format('Y-d-m'),
            'min_temperature' => $this->min_temperature,
            'max_temperature' => $this->max_temperature,
            'condition' => $this->condition,
        ];
    }
}
