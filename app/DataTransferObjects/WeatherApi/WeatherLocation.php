<?php

namespace App\DataTransferObjects\WeatherApi;

use Spatie\LaravelData\Data;

class WeatherLocation extends Data
{
    public function __construct(
        public string $name,
        public string $country,
    ) {}
}
