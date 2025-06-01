<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InventorySyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $spreadsheetId;
    protected $range;
    protected $warehouseId;
    protected $vendor_id;
    protected $sheetName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $spreadsheetId, string $range = 'A2:K', ?int $warehouseId = null, $vendor_id, string $sheetName)
    {
        $this->spreadsheetId = $spreadsheetId;
        $this->range = $range;
        $this->warehouseId = $warehouseId;
        $this->vendor_id = $vendor_id;
        $this->sheetName = $sheetName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Initialize Google Sheets API client
            $client = new Client();
            $client->setApplicationName('Inventory Sync');
            $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
            $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION')); // You'll need to place your Google API credentials here

            $service = new Sheets($client);

            // Get the warehouse (use first warehouse if not specified)
            $warehouse = $this->warehouseId ?
                Warehouse::findOrFail($this->warehouseId) :
                Warehouse::first();

            if (!$warehouse) {
                throw new \Exception('No warehouse found for inventory sync');
            }

            // Fetch data from Google Sheets with specific sheet name
            $response = $service->spreadsheets_values->get(
                $this->spreadsheetId,
                // Include sheet name in the range
                $this->sheetName . '!' . $this->range
            );

            $rows = $response->getValues();

            // Log::debug('All row: ' . json_encode($rows));


            foreach ($rows as $row) {
                // Skip empty rows
                if (empty($row[0])) {
                    Log::debug('Empty row: ' . json_encode($row));
                    continue;
                };

                $sku = $row[0];
                $availableQty = (int)$row[1];

                // $onhand = (int)$row[8] + (int)$row[9] - (int)$row[11];
                $delivered = (int)$row[2];
                $inTransit = (int)$row[2];
                $awaitingReturn = (int)$row[4];
                $awaitingDispatch = (int)$row[5];
                $committed = (int)$row[6];
                $returned = (int)$row[7];
                $onhand = $availableQty + $committed + $delivered;

                // Find product by SKU
                $product = Product::where('product_name', $sku)->where('vendor_id', $this->vendor_id)->first();

                if (!$product) {
                    Log::warning("Product with SKU {$sku} not found during inventory sync");
                    continue;
                }


                // Update inventory based on stock management type
                if ($product->stock_management == 1) {
                    Log::info("Inventory Sync Job: Product found: {$product->product_name} {$this->sheetName}");
                    // Warehouse-level inventory
                    ProductInventory::updateOrCreate(
                        [
                            'warehouse_id' => $warehouse->id,
                            'product_id' => $product->id,
                        ],
                        [
                            'onhand' => $onhand,
                            'delivered' => $delivered,
                            'commited' => $committed,
                            'available_for_sale' => $availableQty
                            // 'available_for_sale' => $availableQty - $committed
                        ]
                    );
                } elseif ($product->stock_management == 2) {
                    Log::info("Bin Sync Job: Product found: {$product->product_name} {$this->sheetName}");
                    // Bin-level inventory - update first bin for simplicity
                    $productBin = ProductBin::where('warehouse_id', $warehouse->id)
                        ->where('product_id', $product->id)
                        ->first();

                    if ($productBin) {
                        $productBin->update([
                            'onhand' => $onhand,
                            'delivered' => $delivered,
                            'commited' => $committed,
                            'available_for_sale' => $availableQty
                            // 'available_for_sale' => $availableQty - $committed
                        ]);
                    } else {
                    $productBin = ProductBin::create([
                        'warehouse_id' => $warehouse->id,
                        'bin_id' => Bin::first()->id,
                        'product_id' => $product->id,
                        'onhand' => $onhand,
                        'delivered' => $delivered,
                        'commited' => $committed,
                        'available_for_sale' => $availableQty
                        // 'available_for_sale' => $availableQty - $committed
                    ]);
                    Log::info("Bin Sync Job: Created new bin for product: {$product->product_name} {$this->sheetName}");
                    }
                }
            }

            Log::info('Inventory sync completed successfully');
        } catch (\Exception $e) {
            Log::error('Inventory sync failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
