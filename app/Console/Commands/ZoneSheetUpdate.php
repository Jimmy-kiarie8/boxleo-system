<?php

namespace App\Console\Commands;

use App\Jobs\GoogleAgentSyncJob;
use App\Jobs\GoogleSyncJob;
use App\Models\Sheet;
use App\Models\ZoneSheet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZoneSheetUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:zone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update google-sheet from the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $delayBetweenJobs = 20; // Introduce a delay of 10 seconds between jobs
        $zones = ZoneSheet::where('active', true)->get();

        try {
            foreach ($zones as $key => $zone) {
                GoogleAgentSyncJob::dispatch($zone->id)->delay(now()->addSeconds($delayBetweenJobs * $key));
            }

        } catch (\Exception $e) {
            // Log::error($e);
            // Log::error('Error dispatching GoogleAgentSyncJob: ' . $e->getMessage());
        }
    }
}
