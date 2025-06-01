<?php

namespace App\Console\Commands;

use App\Jobs\OrderAssignJob;
use App\Models\Ou;
use Illuminate\Console\Command;

class OrderAssignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boxleo:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $ous = Ou::where('active', true)->get();

        $delayBetweenJobs = 30; // Introduce a delay of 10 seconds between jobs
        foreach ($ous as $key => $ou) { 
            if ($ou->id == 4) {
                continue;
            }
            OrderAssignJob::dispatch($ou)->delay(now()->addSeconds($delayBetweenJobs * $key));
        }
    }
}
