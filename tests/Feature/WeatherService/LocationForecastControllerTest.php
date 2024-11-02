<?php

namespace Tests\Feature\Weather;

use App\DataTransferObjects\WeatherApi\WeatherApiResponseData;
use App\Exceptions\LocationForecastException;
use App\Models\User;
use App\Services\WeatherApiService;
use Tests\TestCase;

class LocationForecastControllerTest extends TestCase
{
    protected WeatherApiService $weatherApiService;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
        $this->weatherApiService = new WeatherApiService;
    }

    public function test_fetch_weather_forecast_successfully(): void
    {
        $responseFromApiSample = json_decode(
            file_get_contents(__DIR__.'/../../Stubs/WeatherApiResponse.json'),
            true
        );

        $dataForDTO = [
            'list' => $responseFromApiSample['response']['list'],
            'city' => $responseFromApiSample['response']['city'],
        ];

        $this->mock(WeatherApiService::class)
            ->shouldReceive('getWeatherForecast')
            ->with('London', 'UK')
            ->once()
            ->andReturn(
                WeatherApiResponseData::from($dataForDTO)
            );

        $response = $this->actingAs($this->user, 'sanctum')->call('GET', '/api/v1/get-location-forecast', [
            'city' => 'London',
            'state' => 'UK',
        ]);

        $response->assertStatus(200);
    }

    public function test_fetch_weather_forecast_has_correct_return_format(): void
    {
        $responseFromApiSample = json_decode(
            file_get_contents(__DIR__.'/../../Stubs/WeatherApiResponse.json'),
            true
        );

        $dataForDTO = [
            'list' => $responseFromApiSample['response']['list'],
            'city' => $responseFromApiSample['response']['city'],
        ];

        $this->mock(WeatherApiService::class)
            ->shouldReceive('getWeatherForecast')
            ->with('London', 'UK')
            ->once()
            ->andReturn(
                WeatherApiResponseData::from($dataForDTO)
            );

        $response = $this->actingAs($this->user, 'sanctum')->call('GET', '/api/v1/get-location-forecast', [
            'city' => 'London',
            'state' => 'UK',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'date',
                'min_temperature',
                'max_temperature',
                'condition',
                'icon',
                'city',
                'state',
            ],
        ]);
    }

    public function test_fetch_weather_forecast_has_incorrect_return_format(): void
    {
        $this->mock(WeatherApiService::class)
            ->shouldReceive('getWeatherForecast')
            ->with('London', 'UK')
            ->once()
            ->andThrow(new LocationForecastException('Failed to retrieve weather forecast data'));

        $response = $this->actingAs($this->user, 'sanctum')
            ->call('GET', '/api/v1/get-location-forecast', [
                'city' => 'London',
                'state' => 'UK',
            ]);

        $response->assertStatus(500);
    }
}
