<?php

namespace Tests\Feature;

use App\Services\WeatherApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherApiServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WeatherApiService $weatherApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherApiService = new WeatherApiService();
    }

    public function test_it_can_fetch_weather_forecast_successfully(): void
    {
        $response = $this->weatherApiService->getWeatherForecast('London', 'UK');

        $this->assertArrayHasKey('data', $response);
    }

    public function test_it_returns_error_on_failed_request(): void
    {
        $response = $this->weatherApiService->getWeatherForecast('InvalidCity', 'InvalidState');

        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertEquals('An error occurred while fetching the weather forecast.', $response['message']);
    }
}
