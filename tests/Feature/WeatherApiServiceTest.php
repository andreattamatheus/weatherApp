<?php

namespace Tests\Feature;

use App\DataTransferObjects\WeatherApi\WeatherApiResponseData;
use App\Models\User;
use App\Services\LocationForecastService;
use App\Services\WeatherApiService;
use Illuminate\Support\Facades\Request;
use Mockery;
use Tests\TestCase;

class WeatherApiServiceTest extends TestCase
{
    protected LocationForecastService $locationForecastService;

    protected WeatherApiService $weatherApiService;

    protected string $apiUrl;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
        $this->apiUrl = config('services.openWeather.url') . "/*";
        $this->locationForecastService = new LocationForecastService();
        $this->weatherApiService = new WeatherApiService();
    }

    public function test_fetch_weather_forecast_successfully(): void
    {
        $responseFromApiSample = json_decode(
            file_get_contents(__DIR__ . '/../Stubs/WeatherApiResponse.json'),
            true
        );

        $dataForDTO = [
            'list' => $responseFromApiSample['response']['list'],
            'city' => $responseFromApiSample['response']['city']
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
            file_get_contents(__DIR__ . '/../Stubs/WeatherApiResponse.json'),
            true
        );

        $dataForDTO = [
            'list' => $responseFromApiSample['response']['list'],
            'city' => $responseFromApiSample['response']['city']
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
                "date",
                "min_temperature",
                "max_temperature",
                "condition",
                "icon",
                "city",
                "state",
            ],
        ]);
    }

    public function test_fetch_weather_forecast_has_incorrect_return_format(): void
    {
        $responseFromApiSample = json_decode(
            file_get_contents(__DIR__ . '/../Stubs/WeatherApiResponse.json'),
            true
        );

        $dataForDTO = [
            'list' => $responseFromApiSample['response']['list'],
            'state' => $responseFromApiSample['response']['city']
        ];

        $this->mock(LocationForecastService::class)
            ->shouldReceive('getMostRecentForecast')
            ->with(Mockery::any(), Mockery::any())
            ->once()
            ->andReturn(
                WeatherApiResponseData::from($dataForDTO)
            );

        $response = $this->actingAs($this->user, 'sanctum')->call('GET', '/api/v1/get-location-forecast', [
            'city' => 'London',
            'state' => 'UK',
        ]);

        $response->assertStatus(500);
    }

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
