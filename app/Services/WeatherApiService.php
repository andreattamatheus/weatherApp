<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WeatherApiService
{
    protected string $apiKey;

    protected string $baseUri;

    protected array $headers;


    public function __construct()
    {
        $this->headers = [
            'token' =>config('services.openWeather.key')
        ];
        $this->baseUri = config('services.openWeather.url');
    }

    /**
     * @param array $params
     * @param string $method
     */
    public function sendRequest(string $endpoint = '', array $params = [], string $method = 'get'): array
    {
        try {
            $method = strtolower($method);
            $response = Http::withHeaders($this->headers)
                ->acceptJson()
                ->$method($this->baseUri . $endpoint, ['params' => $params]);

            return $this->decodeResponse($response);
        } catch (RequestException|\JsonException $e) {
            return ['success' => false, 'msg' => 'Invalid response'];
        }
    }

    /**
     * @param $response
     * @return array
     * @throws \JsonException
     */
    public function decodeResponse($response): array
    {
        if (!($response instanceof Response)){
            \Log::alert('Invalid response from weather API');
            return ['success' => false, 'msg' => 'Invalid response'];
        }
        \Log::alert('Valid response from weather API');
        return json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR) ?? [];
    }

    /**
     * Fetch the weather forecast for a given city and state.
     *
     * @param string $city
     * @param string $state
     * @return array
     */
    public function getWeatherForecast(string $city, string $state)
    {
        $params = [
            'q' => $city . ',' . $state,
            'units' => 'imperial'
        ];

        return $this->sendRequest('forecast', $params);
    }
}
