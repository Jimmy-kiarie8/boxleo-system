<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupTidy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:tidy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove backup files that are over one month old.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = Storage::disk('google')->files();

        foreach ($files as $file) {

            $modified = Storage::disk('google')->lastModified($file);
            $date     = Carbon::createFromTimestampUTC($modified);

            if ($date < Carbon::now()->subWeeks(2)) {
                Storage::disk('google')->delete($file);
                $this->info("Deleted " . $file);
            }
        }

        // Delete old backups from spaces
        $directories = ['/boxleo/backups', '/boxleo/waybills', '/boxleo/audios'];

        foreach ($directories as $directory) {
            $allFiles = Storage::disk('spaces')->allFiles($directory);
            foreach ($allFiles as $file) {
                // Check if the file is within the specified directory
                if (strpos($file, $directory) === 0) {
                    $modified = Storage::disk('spaces')->lastModified($file);
                    $date = Carbon::createFromTimestampUTC($modified);


                    try {
                        if ($directory === '/boxleo/audios') {
                            if ($date < Carbon::now()->subMonths(2)) {
                                Storage::disk('spaces')->delete($file);
                                $this->info("Deleted " . $file);
                            }
                        } elseif ($directory === '/boxleo/waybills') {
                            if ($date < Carbon::now()->subWeek()) {
                                Storage::disk('spaces')->delete($file);
                                $this->info("Deleted " . $file);
                            }
                        } else {
                            if ($date < Carbon::now()->subMonth()) {
                                Storage::disk('spaces')->delete($file);
                                $this->info("Deleted " . $file);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to delete {$file}: " . $e->getMessage());
                    }
                    


                }
            }
        }
    }
}
