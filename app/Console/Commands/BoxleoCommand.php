<?php

namespace App\Console\Commands;

use App\Jobs\AudioJob;
use App\Jobs\GeocodeJob;
use App\Jobs\InventorySyncJob;
use App\Jobs\OrderAssignJob;
use App\Jobs\ReminderJob;
use App\Jobs\RoutingJob;
use App\Jobs\StockJob;
use App\Jobs\TransitJob;
use App\Jobs\WaybillJob;
use App\Models\Sheet;
use App\Models\User;
use Carbon\Carbon;
use Google\Service\Sheets;
use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Sheets as ServiceSheets;
use Illuminate\Support\Facades\Log;


class BoxleoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boxleo:command';
    // protected $signature = 'boxleo:command {spreadsheetId} {range} {warehouseId}';

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
        // $spreadsheetId = $this->argument('spreadsheetId');
        // $range = $this->argument('range');
        // $warehouseId = $this->argument('warehouseId');

        $sheets = [
            [
                'spreadsheetId' => '1WnnVWu8Lmcqt3-l1LT4eBJrIVKTbUWxVkt4B_diHdyM',
                'range' => 'A3:K',
                'warehouseId' => 1,
                'vendor_id' => 327,
                'sheetName' => '2025 AUTO INVENTORY SHEET',
                'vendor_name' => 'East Africa COD',
            ],
            [
                'spreadsheetId' => '1Tuc2Y6NjYChufzFpJ2MLqjfTF_jh8gk8D4kPHZtKYbU',
                'range' => 'A5:K',
                'warehouseId' => 1,
                'vendor_id' => 107,
                'sheetName' => '2025 AUTO INVENTORY UPDATE',
                'vendor_name' => 'Maryshop',
            ],
            [
                'spreadsheetId' => '1lDfS0H8soB-y32eKV6-JfELOAKUO0FjVAOdryqppjV0',
                'range' => 'A4:K',
                'warehouseId' => 1,
                'vendor_id' => 307,
                'sheetName' => '2025 Auto Inventory Update',
                'vendor_name' => 'Fitmall',
            ],
            [
                'spreadsheetId' => '1mi0_C3rPKKyNymPYe5fhEQ1KDa31DZsp49tdzC4yiY4',
                'range' => 'A4:K',
                'warehouseId' => 1,
                'vendor_id' => 54,
                'sheetName' => 'NSL AUTO INVENTORY UPDATE',
                'vendor_name' => 'Narjis',
            ],
            [
                'spreadsheetId' => '1rCboq2FXx25fRMdIH2NmOmV_Zr-2qaorbPXiDE1seY4',
                'range' => 'A6:K',
                'warehouseId' => 1,
                'vendor_id' => 175,
                'sheetName' => '2025 AUTO INVENTORY SHEET',
                'vendor_name' => 'Bnh',
            ],
            [
                'spreadsheetId' => '1_7oLs-KedrUN2BcvCB_BD0g98Gb4eAdaV6nnPFoAhJ8',
                'range' => 'A2:K',
                'warehouseId' => 1,
                'vendor_id' => 47,
                'sheetName' => '2025 AUTO INVENTORY SHEET',
                'vendor_name' => 'Kny',
            ]
        ];

        foreach ($sheets as $sheet) {
            InventorySyncJob::dispatch($sheet['spreadsheetId'], $sheet['range'], $sheet['warehouseId'], $sheet['vendor_id'], $sheet['sheetName']);
            // sleep(4);
        }

        return;
        // GeocodeJob::dispatch();
        // AudioJob::dispatch();
        // return;

        $user = User::first();
        $data = [
            'start_date' => Carbon::today()->subWeeks(3),
            'end_date' => Carbon::today(),
            'vendor_id' => 1
        ];
        WaybillJob::dispatch($user, collect($data));

        return;
        $sheets = Sheet::where('ou_id', '!=', 4)->where('is_current', true)->where('active', true)->first();
        $delayBetweenJobs = 20; // Introduce a delay of 10 seconds between jobs

        // Begin a single database transaction
        // DB::beginTransaction();

        try {
            $client = new Client();
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            // $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            $client->addScope(ServiceSheets::SPREADSHEETS);
            $service = new ServiceSheets($client);
            $valueInputOption = 'RAW';

            $spreadsheetId = '1koZukyrXQpKVK00PLH_fCXpYny4brwHWSenWFywgYG8';
            $sheet_name = 'Sheet1';
            $status = 'Scheduled';

            // Define the filter query
            $filter = "status = '$status' and timestamp >= '" . date('Y-m-d\TH:i:sP', strtotime('-24 hours')) . "'";

            // Create the request body with the filter
            $requestBody = [
                'dataFilters' => [
                    [
                        'gridRange' => [
                            'sheetId' => $spreadsheetId, // You need to get the correct sheetId
                        ],
                        'developerMetadataLookup' => [
                            'metadataLocation' => [
                                'locationType' => 'ROW', // Adjust as needed
                            ],
                            'metadataKey' => 'status', // Adjust as needed
                            'metadataValue' => $status,
                        ],
                        'a1Range' => $sheet_name, // Specify the range as needed
                        'dataFilter' => $filter,
                    ],
                ],
                'valueRenderOption' => 'FORMATTED_VALUE',
            ];

            // Make the request to retrieve filtered data
            $response = $service->spreadsheets_values->batchGet($spreadsheetId, $requestBody);
            $values = $response->getValueRanges()[0]->getValues();

            return $values;
        } catch (\Exception $e) {
            // Log::error($e);
        }
    }
}
