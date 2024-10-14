<?php

namespace App\DataTransferObjects\WeatherApi;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class WeatherData extends Data
{
    public function __construct(
        public string $dt_txt,
        public WeatherTempData $main,
        /** @var array<WeatherConditionData> */
        public array $weather
    ) {
        $this->dt_txt = Carbon::createFromTimestamp($dt_txt)->format('Y-d-m');
    }
}
