<?php

namespace App\DataTransferObjects\WeatherApi;

use Spatie\LaravelData\Data;

class WeatherConditionData extends Data
{
    public function __construct(
        public string $description,
        public string $icon
    ) {}
}
