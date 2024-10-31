<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\CreateLocationForecast;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

class UserControllerTest extends TestCase
{
    protected $user;
    protected $location;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
        $this->location = Location::factory()->make();
    }

    public function test_store_location_job_dispatched_successfully()
    {
        Bus::fake();

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
        Bus::assertDispatched(CreateLocationForecast::class);
    }

    public function test_store_location_job_dispatched_failed()
    {
        Bus::fake();

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

        Bus::assertNotDispatched(CreateLocationForecast::class);
    }

    public function test_destroy_location_successfully()
    {
        $locationId = $this->location->id;
        $date = $this->location->date;

        $response = $this->actingAs($this->user, 'sanctum')->delete("/api/v1/users/locations/{$locationId}/{$date}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Location deleted successfully!',
            ]);
    }
}
