<?php

namespace App\Console\Commands;

use App\Jobs\ProcessOrdersJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return Command::SUCCESS;


        $job = new ProcessOrdersJob();
        dispatch($job);
        $this->info('Done!');

        return;
        $url = 'https://boxleocourier.com/dashboard/api/v1/fetch-orders/100';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get($url);

        $ordersData =  json_decode($response->getBody()->getContents());
        $lastPage = $ordersData->last_page;


        $perPage = 100; // Adjust this as needed
        // $lastPage = 3061;
        $jobs = [];

        for ($i = 1; $i <= $lastPage; $i++) {
            $jobs[] = new ProcessOrdersJob($i, $perPage);

            // Dispatch the batch if the batch size reaches a certain limit
            if (count($jobs) === 100) {
                Bus::batch($jobs)->dispatch();
                $jobs = [];
            }
        }

        // Dispatch any remaining jobs as the final batch
        if (count($jobs) > 0) {
            Bus::batch($jobs)->dispatch();
        }

        $this->info('Done!');

    }
}
