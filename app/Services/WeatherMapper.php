<?php

namespace App\Services;

use App\Models\LocationForecast;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class WeatherMapper
{
    public function mapLocation(array $apiResponse)
    {
        return [
            'id' => $apiResponse['id'],
            'city' => $apiResponse['city'],
            'forecasts' => $this->mapForecasts($apiResponse['forecasts']),
        ];
    }

    public function mapForecasts(Collection $forecasts)
    {
        return $forecasts->map(function ($forecast) {
            return $this->mapForecast($forecast);
        })->toArray();
    }

    public function mapForecast(LocationForecast $forecast)
    {
        return [
            'date' => Carbon::parse($forecast['date'])->format('Y-d-m'),
            'min_temperature' => $forecast['min_temperature'],
            'max_temperature' => $forecast['max_temperature'],
            'condition' => $forecast['condition'],
            'icon' => $forecast['icon'],
        ];
    }

    public function getMostRecentForecast(array $response): array
    {
        $mostRecentForecast = $response['data']['list'][count($response['data']['list']) - 1];
        $cityData = $response['data']['city'];

        return [
            'date' => Carbon::parse($mostRecentForecast['dt'])->format('Y-d-m'),
            'min_temperature' => $mostRecentForecast['main']['temp_min'],
            'max_temperature' => $mostRecentForecast['main']['temp_max'],
            'condition' => $mostRecentForecast['weather'][0]['description'],
            'icon' => $mostRecentForecast['weather'][0]['icon'],
            'city' => $cityData['name'],
            'state' => $cityData['country'],
        ];
    }
}
