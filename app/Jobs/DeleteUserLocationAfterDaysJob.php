<?php

namespace App\Jobs;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteUserLocationAfterDaysJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger()->channel('daily')->info('Deleting locations older than 3 days.');
        $locations = Location::query()->where('created_at', '<', now()->subDays(3))->get();
        DB::transaction(static function () use ($locations) {
            foreach ($locations as $location) {
                $location->delete();
            }
        });
        logger()->channel('daily')->info('Locations older than 3 days have been deleted.');
    }
}
