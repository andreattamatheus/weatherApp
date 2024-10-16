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
    public function test_store_location_success()
    {
        // Fake the queue for the CreateLocationForecast job
        Queue::fake();

        // Get a user
        $user = User::factory()->make();

        // Prepare the request data
        $requestData = [
            'city' => 'Test City',
            'state' => 'Test State',
            'date' => now()->toDateString(),
            'min_temperature' => '15',
            'max_temperature' => '30',
            'condition' => 'Sunny',
        ];

        // Send the POST request
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/users/locations', $requestData);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Location saved successfully!',
            ]);

        // Check that the job was dispatched with correct data
        Queue::assertPushed(CreateLocationForecast::class, function ($job) use ($requestData, $user) {
            $weatherData = ForecastResource::make($requestData)->resolve();
            return $job->weatherData === $weatherData && $job->user->is($user);
        });
    }

    public function test_store_location_failure()
    {
        // Fake the queue and the logger
        Queue::fake();

        // Get a user
        $user = User::factory()->make();

        // Send the POST request with invalid data (e.g., missing 'city')
        $requestData = [
            'state' => 'Test State',
            'date' => now()->toDateString(),
            'min_temperature' => '15',
            'max_temperature' => '30',
            'condition' => 'Sunny',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/users/locations', $requestData);

        // Assert the failure response
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'city'
            ]);

        // Ensure the job was not dispatched
        Queue::assertNotPushed(CreateLocationForecast::class);
    }
}
