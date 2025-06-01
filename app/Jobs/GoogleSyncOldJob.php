<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\Sheet;
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

class GoogleSyncOldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
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

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(ServiceSheets::SPREADSHEETS);
        $service = new ServiceSheets($client);
        $valueInputOption = 'RAW';

        $id = $this->id;
        $sheets = Sheet::where('ou_id', '!=', 4)->where('active', true)->with(['vendor'])->find($id);
        $spreadsheetId = $sheets->post_spreadsheet_id;
        $sheet_name = $sheets->sheet_name;
        $vendor_id = $sheets->vendor_id;
        $last_order_synced = $sheets->last_order_synced;
        // dd($last_order_synced);
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

        $orders = Sale::whereIn('order_no', $filteredArray)->where('seller_id', $vendor_id)
            ->take(1000)
            ->latest()
            ->where('delivery_status', '!=', 'New')
            ->when($last_order_synced, function ($q) use ($last_order_synced) {
                $q->where('updated_at', '>=', $last_order_synced);
            })
            ->setEagerLoads([])
            ->with(['client' => function ($q) {
                return $q->setEagerLoads([]);
            }, 'agent' => function ($q) {
                return $q->setEagerLoads([]);
            }])->get(['client_id', 'delivery_status', 'delivery_date', 'customer_notes', 'order_no', 'agent_id', 'return_notes', 'total_price', 'updated_at']);
            $orders->transform(function ($order) {

            $order->customer_notes = ($order->return_notes) ? $order->return_notes : $order->customer_notes;
            $order->client_address = ($order->client) ? $order->client->address : null;
            $order->agent_name = ($order->agent) ? $order->agent->name : null;

            return $order;
        });
        if (count($orders) < 1) {
            return;
        } else {
            $last_updated_orderNumber = null;

            try {
                foreach ($orders as $order) {
                    // dd($order['client_address']);

                    $orderNumber = $order['order_no'];
                    $delivery_date = ($order['delivery_date']) ? Carbon::parse($order['delivery_date'])->format('Y-m-d') : Carbon::parse($order['updated_at'])->format('Y-m-d');
                    $delivery_status = ($order['delivery_status']) ? $order['delivery_status'] : '';
                    $customer_notes = ($order['customer_notes']) ? $order['customer_notes'] : '';
                    $address = $order['client_address'];
                    // $cod_amount = $order['total_price'];
                    $agent_name = $order['agent_name'];
                    $updatedData = [
                        $delivery_status,
                        $delivery_date,
                        $customer_notes,
                    ];

                    if ($agent_name) {
                        array_push($updatedData, $agent_name);
                    }


                    // Check if any data is updated
                    // if (array_diff($updatedData, $values[$rowIndex])) {
                    //     $dataUpdated = true;

                    //     // Update the data array...
                    // }
                    // Retrieve the range based on the order number
                    $range = $this->getRangeByOrderNumber($values, $order_values, $orderNumber, $sheet_name, 'Status');
                    if ($range) {
                        $data[] = new ValueRange([
                            'range' => $range,
                            'values' => [$updatedData],
                        ]);
                        $last_updated_orderNumber = $orderNumber;
                    }

                    if ($address) {
                        $updatedData_2 = [
                            $address
                        ];
                        $range_2 = $this->getRangeByOrderNumber($values, $order_values, $orderNumber, $sheet_name, 'Address');
                        if ($range_2) {
                            $data[] = new ValueRange([
                                'range' => $range_2,
                                'values' => [$updatedData_2],
                            ]);
                        }
                    }

                    // if ($cod_amount) {
                    //     $updatedData_3 = [
                    //         $cod_amount
                    //     ];
                    //     $range_3 = $this->getRangeByOrderNumber($values, $order_values, $orderNumber, $sheet_name, 'Cod Amount');
                    //     if ($range_3) {
                    //         $data[] = new ValueRange([
                    //             'range' => $range_3,
                    //             'values' => [$updatedData_3],
                    //         ]);
                    //     }
                    // }
                }
                // return $data;

                $body = new BatchUpdateValuesRequest([
                    'valueInputOption' => $valueInputOption,
                    'data' => $data,
                ]);
                $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
                // printf("%d cells updated.", $result->getTotalUpdatedCells());


                $lock = Cache::lock('orderSync', 5);

                try {
                    $lock->block(5);
                    if ($last_updated_orderNumber) {
                        $sheets->update(['last_order_synced' => Carbon::now()->subMinutes(2)]);
                    }
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
