<?php

namespace App\DataTransferObjects\WeatherApi;

use Spatie\LaravelData\Data;

class WeatherApiResponseData extends Data
{
    public function __construct(
        /** @var array<WeatherData> */
        public array $list,
        public WeatherLocation $city
    ) {}
}
