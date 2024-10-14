<?php

namespace App\DataTransferObjects\WeatherApi;

use Spatie\LaravelData\Data;

class WeatherTempData extends Data
{
    public function __construct(
        public float $temp_min,
        public float $temp_max
    ) {}
}
