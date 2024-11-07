<?php

namespace App\Services;

use App\Models\LocationForecast;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class WeatherMapper
{
    public function mapLocation(array $apiResponse): array
    {
        return [
            'id' => $apiResponse['id'],
            'city' => $apiResponse['city'],
            'forecasts' => $this->mapForecasts($apiResponse['forecasts']),
        ];
    }

    public function mapForecasts(Collection $forecasts): array
    {
        return $forecasts->map(function ($forecast) {
            return $this->mapForecast($forecast);
        })->toArray();
    }

    public function mapForecast(LocationForecast $forecast): array
    {
        return [
            'date' => Carbon::parse($forecast['date'])->format('Y/m/d'),
            'min_temperature' => $forecast['min_temperature'],
            'max_temperature' => $forecast['max_temperature'],
            'condition' => $forecast['condition'],
            'icon' => $forecast['icon'],
        ];
    }
}
