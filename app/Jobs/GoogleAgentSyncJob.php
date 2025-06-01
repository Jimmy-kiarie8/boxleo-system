<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Sheet;
use App\Models\Zone;
use App\Models\ZoneSheet;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Client;
use Google\Service\Sheets as ServiceSheets;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;

class GoogleAgentSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 360;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $sheets = ZoneSheet::find($this->id);

        $zone_sale = SaleZone::whereIn('zone_to', $sheets->zones)->where('zone_to', '!=', 1)->whereBetween('dispatched_on', [Carbon::now()->subMonth(), Carbon::tomorrow()])->pluck('sale_id');

        // $zone_sale = Zone::where

        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(ServiceSheets::SPREADSHEETS);
        $service = new ServiceSheets($client);
        $valueInputOption = 'RAW';

        $id = $this->id;
        $sheets = ZoneSheet::where('ou_id', 1)->where('active', true)->find($id);
        $spreadsheetId = $sheets->post_spreadsheet_id;
        $sheet_name = $sheets->sheet_name;
        // $last_order_synced = null;
        $last_order_synced = Carbon::now()->subDays(5);
        $data = [];
        $lastUpdatedOrderNumber = $sheets->lastUpdatedOrderNumber;
        $response = $service->spreadsheets_values->get($spreadsheetId, $sheet_name . '!1:1', ['valueRenderOption' => 'UNFORMATTED_VALUE']);
        $values = $response->getValues();
        $order_response = $service->spreadsheets_values->get($spreadsheetId, $sheet_name . '!B:B', ['valueRenderOption' => 'UNFORMATTED_VALUE']);
        $order_values = $order_response->getValues();

        $combinedArray = [];
        foreach ($order_values as $value) {
            $combinedArray = array_merge($combinedArray, $value);
        }
        if ($lastUpdatedOrderNumber) {
            $startIndex = array_search($lastUpdatedOrderNumber, $combinedArray);
            if ($startIndex !== false) {
                $filteredArray = array_slice($combinedArray, $startIndex);
            } else {
                $filteredArray = [];
            }
        } else {
            $filteredArray = $combinedArray;
        }


        $statuses = ['Delivered', 'Returned', 'Awaiting Return'];

        $orders = Sale::whereIn('order_no', $filteredArray)
            ->whereBetween('updated_at', [Carbon::now()->subDays(3), Carbon::now()])
            // ->orWhereBetween('returned_on', [Carbon::now()->subDays(3), Carbon::now()])
            ->whereIn('id', $zone_sale)
            ->whereIn('delivery_status', $statuses)
            ->when($last_order_synced, function ($q) use ($last_order_synced) {
                $q->where('updated_at', '>=', $last_order_synced);
            })
            ->setEagerLoads([])
            ->take(600)
            ->orderBy('updated_at', 'desc')
            ->get(['delivery_status', 'order_no','return_notes', 'mpesa_code']);


        if (count($orders) < 1) {
            Log::info('No orders found for zone ' . $sheets->sheet_name);
            return;
        } else {
            Log::info('Found ' . count($orders) . ' orders for zone ' . $sheets->sheet_name);
            $last_updated_orderNumber = null;

            try {
                foreach ($orders as $order) {

                    $orderNumber = $order['order_no'];
                    $delivery_status = ($order['delivery_status']) ? $order['delivery_status'] : '';
                    $return_notes = ($order['return_notes']) ? $order['return_notes'] : '';
                    $mpesa_code = ($order['mpesa_code']) ? $order['mpesa_code'] : 'No code';
                    $updatedData = [
                        $delivery_status,
                        $mpesa_code,
                        $return_notes
                    ];

                    // Retrieve the range based on the order number
                    $range = $this->getRangeByOrderNumber($values, $order_values, $orderNumber, $sheet_name, 'Status');
                    if ($range) {
                        $data[] = new ValueRange([
                            'range' => $range,
                            'values' => [$updatedData],
                        ]);
                        $last_updated_orderNumber = $orderNumber;
                    }
                }
                // return $data;

                $body = new BatchUpdateValuesRequest([
                    'valueInputOption' => $valueInputOption,
                    'data' => $data,
                ]);
                $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
                // printf("%d cells updated.", $result->getTotalUpdatedCells());


                $lock = Cache::lock('zoneSync', 5);

                try {
                    $lock->block(5);
                    // if ($last_updated_orderNumber) {
                        $sheets->update(['last_order_synced' => Carbon::now()->subMinutes(2)]);
                    // }
                    // Lock acquired after waiting a maximum of 5 seconds...
                } catch (LockTimeoutException $e) {
                    // Log::debug($e);
                    // Unable to acquire lock...
                } finally {
                    optional($lock)->release();
                }

                // DB::commit();

                return;
            } catch (Exception $e) {
                // DB::rollBack();
                // Log::debug($e);
                // TODO(developer) - handle error appropriately
                echo 'Message: ' . $e->getMessage();
            }
        }
    }

    public function getRangeByOrderNumber($values, $columnValues, $orderNumber, $sheet, $column)
    {
        $range = null;
        if (!empty($values)) {
            $headers = $values[0];
            // $statusColumnIndex = array_search('Status', $headers);
            $statusColumnIndex = array_search(strtolower($column), array_map('strtolower', $headers));

            if ($statusColumnIndex !== false) {
                $columnLetter = chr(ord('A') + $statusColumnIndex);

                if (!empty($columnValues)) {
                    foreach ($columnValues as $rowIndex => $row) {
                        // Skip the first row (header row)
                        if ($rowIndex === 0) {
                            continue;
                        }

                        if (!empty($row)) {
                            if ($row[0] == $orderNumber) {
                                // Offset row index by 1 to match Google Sheets index (1-based)
                                $range = $sheet . '!' . $columnLetter . ($rowIndex + 1);
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $range;
    }
}
