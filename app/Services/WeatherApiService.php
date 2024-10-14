<?php

namespace App\Services;

use App\DataTransferObjects\WeatherApi\WeatherApiResponseData;
use App\Exceptions\LocationForecastException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class WeatherApiService
{
    protected string $apiKey;

    protected string $baseUri;

    protected array $params;


    public function __construct()
    {
        $this->params = [
            'APPID' => config('services.openWeather.key')
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
            $params = array_merge($this->params, $params);
            $response = Http::acceptJson()
                ->$method($this->baseUri . $endpoint, $params);

            return $this->decodeResponse($response, $endpoint);
        } catch (RequestException | \JsonException $e) {
            logger()->channel('weather-api')->error('[Open Weather API - Request] - ' . $endpoint, [
                'PID' => getmypid(),
                'message' => $e->getMessage()
            ]);

            return ['success' => false, 'message' => 'Error occurred while fetching the weather forecast.'];
        }
    }

    /**
     * @param $response
     * @return array
     * @throws \JsonException
     * @throws LocationForecastException
     */
    public function decodeResponse($response, string $endpoint = ''): array
    {
        $decodedResponse = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        if ($response->failed()) {
            logger()->channel('weather-api')->error('[Open Weather API - Response] - ' . $endpoint, [
                'PID' => getmypid(),
                'response' => $decodedResponse
            ]);

            return ['success' => false, 'message' => 'An error occurred while fetching the weather forecast.'];
        }
        logger()->channel('weather-api')->info('[Open Weather API - Response] - ' . $endpoint, [
            'PID' => getmypid(),
            'response' => $decodedResponse
        ]);

        return ['success' => true, 'data' => $decodedResponse];
    }

    /**
     * Fetch the weather forecast for a given city and state.
     *
     * @param string $city
     * @param string $state
     * @return WeatherApiResponseData
     */
    public function getWeatherForecast(string $city, string $state): WeatherApiResponseData
    {
        $params = [
            'q' => $city . ',' . $state,
            'units' => 'metric'
        ];

        $response = $this->sendRequest('forecast', $params);

        return WeatherApiResponseData::from($response['data']);
    }
}
