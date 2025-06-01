<?php

namespace App\Console\Commands;

use App\Jobs\GoogleSyncJob;
use App\Models\Sheet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoogleSheetUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:update';

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


    // public function handle()
    // {
    //     $sheets = Sheet::where('active', true)->get();

    //     $delayBetweenJobs = 20; // Introduce a delay of 10 seconds between jobs

    //     foreach ($sheets as $key => $sheet) {
    //     DB::beginTransaction();

    //         GoogleSyncJob::dispatch($sheet->id)->delay(now()->addSeconds($delayBetweenJobs * $key));
    //     }

    // }

    public function handle()
    {
        $sheets = Sheet::where('is_current', true)->where('active', true)->get();
        // $sheets = Sheet::where('ou_id', '!=', 4)->where('is_current', true)->where('active', true)->get();
        $delayBetweenJobs = 20; // Introduce a delay of 10 seconds between jobs

        // Begin a single database transaction
        // DB::beginTransaction();

        try {
            foreach ($sheets as $key => $sheet) {
                GoogleSyncJob::dispatch($sheet->id)->delay(now()->addSeconds($delayBetweenJobs * $key));
            }

            // $sheets->update(['last_order_synced' => Carbon::now()->subMinutes(2)]);


            // Commit the transaction after all jobs are dispatched
            // DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an exception
            // DB::rollBack();
            // Handle the exception, log or report it
            // Log::error($e);
            // Log::error('Error dispatching GoogleSyncJob: ' . $e->getMessage());
        }
    }

}
