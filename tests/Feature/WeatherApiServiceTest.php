<?php

namespace Tests\Feature;

use App\Services\WeatherApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
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
        // Mock the HTTP response
        Http::fake([
            'forecast*' => Http::response([
                'cod' => '200',
                'message' => 0,
                'list' => [
                    [
                        'dt' => 1631507200,
                        'main' => [
                            'temp' => 20,
                            'feels_like' => 19,
                            'temp_min' => 15,
                            'temp_max' => 22,
                        ],
                        'weather' => [
                            ['description' => 'clear sky']
                        ],
                    ]
                ],
            ], 200),
        ]);

        // Call the method
        $response = $this->weatherApiService->getWeatherForecast('London', 'UK');

        // Assert that the response is correct
        $this->assertArrayHasKey('list', $response);
        $this->assertEquals('clear sky', $response['list'][0]['weather'][0]['description']);
    }

    public function test_it_returns_error_on_failed_request(): void
    {
        // Mock the HTTP response with an error
        Http::fake([
            'forecast*' => Http::response(['success' => false, 'msg' => 'Invalid response'], 500),
        ]);

        // Call the method
        $response = $this->weatherApiService->getWeatherForecast('InvalidCity', 'InvalidState');

        // Assert that the response indicates failure
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertEquals('Invalid response', $response['msg']);
    }
}
