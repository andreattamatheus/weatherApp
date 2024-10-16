<?php

namespace Tests\Feature;

use App\Http\Resources\ForecastResource;
use Tests\TestCase;
use App\Jobs\CreateLocationForecast;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class UserControllerTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
    }

    public function test_store_location_success()
    {
        Queue::fake();

        $requestData = [
            'city' => 'Test City',
            'state' => 'Test State',
            'date' => now()->toDateString(),
            'min_temperature' => '15',
            'max_temperature' => '30',
            'condition' => 'Sunny',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/users/locations', $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Location saved successfully!',
            ]);

        // Check that the job was dispatched with correct data
        Queue::assertPushed(CreateLocationForecast::class, function ($job) use ($requestData) {
            $weatherData = ForecastResource::make($requestData)->resolve();
            return $job->weatherData === $weatherData && $job->user->is($this->user);
        });
    }

    public function test_store_location_failure()
    {
        Queue::fake();

        $requestData = [
            'state' => 'Test State',
            'date' => now()->toDateString(),
            'min_temperature' => '15',
            'max_temperature' => '30',
            'condition' => 'Sunny',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/v1/users/locations', $requestData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'city'
            ]);

        // Ensure the job was not dispatched
        Queue::assertNotPushed(CreateLocationForecast::class);
    }
}
