<?php

use App\Jobs\CreateLocationForecast;
use App\Models\Location;
use App\Models\LocationForecast;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->location = Location::factory()->create([
        'user_id' => $this->user->id,
    ]);
    $this->locationForecast = LocationForecast::factory()->create([
        'location_id' => $this->location->id,
    ]);
});

test('get users locations successfully', function () {
    $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/v1/users/locations');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                0 => [
                    'id',
                    'city',
                    'forecasts' => [
                        '*' => [
                            'date',
                            'min_temperature',
                            'max_temperature',
                            'condition',
                        ],
                    ],
                ],
            ],
        ]);
});

test('store location job dispatched successfully', function () {
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
            'message' => 'Location register successfully!',
        ]);

    Bus::assertDispatched(CreateLocationForecast::class);
});

test('store location job dispatched failed', function () {
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
            'city',
        ]);

    Bus::assertNotDispatched(CreateLocationForecast::class);
});

test('destroy location successfully', function () {
    $locationId = $this->location->id;
    $date = $this->locationForecast->date;

    $this->actingAs($this->user, 'sanctum')->delete("/api/v1/users/locations/{$locationId}/{$date}");

    $this->assertDatabaseMissing('location_forecasts', [
        'location_id' => $locationId,
        'date' => $date,
        'deleted_at' => null,
    ]);
});

test('destroy location faild', function () {
    $locationId = $this->location->id;
    $date = Carbon::today()->addDays(1)->format('Y-d-m');

    $response = $this->actingAs($this->user, 'sanctum')->delete("/api/v1/users/locations/{$locationId}/{$date}");

    $response->assertStatus(500);
});
