<?php

namespace Tests\Feature;

use App\Services\WeatherApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherApiServiceTest extends TestCase
{
    protected WeatherApiService $weatherApiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->weatherApiService = new WeatherApiService;
    }

    // public function test_it_can_fetch_weather_forecast_successfully(): void
    // {
    //     Http::fake([
    //         '*' => Http::response([
    //             'data' => [
    //                 'city' => 'London',
    //                 'list' => [
    //                     [
    //                         'main' => ['temp_min' => 10, 'temp_max' => 15],
    //                         'weather' => [['description' => 'clear sky', 'icon' => '01d']],
    //                         'dt' => now()->timestamp,
    //                     ],
    //                 ],
    //             ],
    //         ], 200),
    //     ]);

    //     $response = $this->weatherApiService->getWeatherForecast('London', 'UK');

    //     $this->assertArrayHasKey('data', $response);
    // }

    // public function test_it_returns_error_on_failed_request(): void
    // {
    //     Http::fake([
    //         '*' => Http::response([
    //             'message' => 'City not found',
    //         ], 404),
    //     ]);

    //     $response = $this->weatherApiService->getWeatherForecast('InvalidCity', 'InvalidState');

    //     $this->assertArrayHasKey('success', $response);
    //     $this->assertFalse($response['success']);
    //     $this->assertEquals('An error occurred while fetching the weather forecast.', $response['message']);
    // }
}
