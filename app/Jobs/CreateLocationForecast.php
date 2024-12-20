<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateLocationForecast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $weatherData;

    public User $user;

    /**
     * Create a new job instance.
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
    public function handle(UserService $userService)
    {
        $userService->store($this->weatherData, $this->user);
    }
}
