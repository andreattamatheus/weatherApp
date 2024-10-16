<?php

namespace App\Jobs;

use App\Http\Resources\ForecastResource;
use App\Models\User;
use App\Services\LocationForecastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateLocationForecast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $weatherData;

    protected User $user;

    /**
     * Create a new job instance.
     *
     * @param array $weatherData
     */
    public function __construct(array $weatherData, User $user)
    {
        $this->weatherData = $weatherData;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LocationForecastService $locationForecastService)
    {
        $locationForecastService->store($this->weatherData, $this->user);
    }
}
