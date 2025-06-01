<?php

namespace App\Console\Commands;

use App\Models\Sale;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ArchiveCommand extends Command
{
    
    protected $signature = 'orders:archive';
    // protected $signature = 'orders:archive {days=365 : Number of days old to archive}';
    protected $description = 'Archive orders older than the specified number of days';

    public function handle()
    {
        $days = 365;
        // $days = $this->argument('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Archiving orders older than {$days} days...");

        DB::transaction(function () use ($cutoffDate) {
            $count = Sale::whereNull('archived_at')
                ->where('created_at', '<', $cutoffDate)
                ->update(['archived_at' => Carbon::now()]);

            $this->info("Archived {$count} orders.");
        });

        $this->info('Archiving complete.');
    }
}
