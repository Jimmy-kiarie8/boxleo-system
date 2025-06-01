<?php

namespace App\Jobs;

use App\Models\ProductInventory;
use App\Models\ReturnSale;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Scopes\OrderScope;
use App\Scopes\ProductScope;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id, $data, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $data, $user)
    {
        $this->id = $id;
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the order date from the data
        $orderDate = Carbon::parse($this->data['created_at']);

        // Determine the start and end of the current half-year
        if ($orderDate->month <= 6) {
            // First half of the year (January 1st to June 30th)
            $startOfHalfYear = Carbon::create($orderDate->year, 1, 1); // January 1st
            $endOfHalfYear = Carbon::create($orderDate->year, 6, 30)->endOfDay(); // June 30th, end of the day
        } else {
            // Second half of the year (July 1st to December 31st)
            $startOfHalfYear = Carbon::create($orderDate->year, 7, 1); // July 1st
            $endOfHalfYear = Carbon::create($orderDate->year, 12, 31)->endOfDay(); // December 31st, end of the day
        }

        // Find the sale within the correct partition using date range
        $sale = Sale::withoutGlobalScope(OrderScope::class)
            ->setEagerLoads([])
            ->from(DB::raw('`sales` FORCE INDEX (PRIMARY)'))
            ->whereBetween('sales.created_at', [$startOfHalfYear, $endOfHalfYear])
            ->with(['products' => function ($query) {
                $query->setEagerLoads([])
                      ->withoutGlobalScope(ProductScope::class)
                      ->select('products.id', 'products.product_name');  // Only load necessary columns
                $query->withPivot('quantity', 'price', 'sku_no');  // Limit pivot data
            }, 'client'])
            ->find($this->id);

        if (!$sale) {
            Log::error('Sale not found in partition', [
                'sale_id' => $this->id,
                'partition_start' => $startOfHalfYear,
                'partition_end' => $endOfHalfYear
            ]);
            throw new \Exception("Sale not found in the expected partition");
        }

        $data = $this->data;

        if ($sale->delivery_status === 'Delivered') {
            if (!$this->user->hasRole('Super admin')) {
                return;
            }
        }

        DB::beginTransaction();

        try {
            $status = $this->data['status'];

            if ($status == 'Dispatched' || $status == 'In Transit') {
                // if previous status was dispatched or in transit, then don't update the dispatched_on date
                if ($sale->delivery_status == 'Dispatched' || $sale->delivery_status == 'In Transit') {
                    $sale->dispatched_on = $sale->dispatched_on;
                } else {
                    $sale->dispatched_on = now();
                }

                // $sale->dispatched_on = now();
                $this->updateSaleZone($data);

                if (array_key_exists('rider_id', $data) && $data['rider_id']) {
                    $rider_sale = new RiderSale();
                    $rider_sale->create($this->id, $this->data['rider_id']);
                }
            } 
            elseif ($status == 'Delivered') {
                if (array_key_exists('amount_paid', $data)) {
                    $sale->amount_paid = $this->data['partial_delivery'] == 'partial_delivery' 
                        ? $this->data['amount_paid'] 
                        : $sale->total_price;
                } else {
                    $sale->amount_paid = $sale->total_price;
                }

                $sale->delivered_on = $this->data['delivered_on'] ?? now();
                $sale->mpesa_code = array_key_exists('mpesa_code', $data) ? $data['mpesa_code'] : null;
                $sale->payment_method = array_key_exists('payment_method', $data) ? $data['payment_method'] : null;
                $sale->paid = true;
            } elseif ($status == 'Returned') {
                $sale->return_count += 1;
                $sale->return_notes = $data['return_notes'];
                $sale->returned_on = $this->data['returned_on'] ?? now();
                $sale->returned = true;
                
                $return_sale = new ReturnSale();
                $return_sale->return_item($sale, $data['comment']);
            } elseif ($status == 'Awaiting Return') {
                $sale->return_date = now();
                $sale->return_notes = $data['return_notes'];
            } elseif ($status == 'Scheduled') {
                $sale->printed = 0;
                $this->updateSaleZone($data);
            } elseif ($status == 'Cancelled') {
                $sale->cancelled_on = $this->data['cancelled_on'] ?? now();
            }

            $sale->customer_notes = $data['comment'];
            $sale->recall_date = $data['recall_date'];
            $sale->delivery_date = $data['delivery_date'] ? $data['delivery_date'] : $sale->delivery_date;
            
            $old_status = $sale->getOriginal('delivery_status');
            $products = $sale->products;

            $sale->user_id = $this->user->id;

            if ($status != $old_status) {
                $sale->history_comment = 'Order status updated from <b style="color:red"> ' . $old_status . '</b> to <b style="color:#1564c0;"> ' . $status;
                $sale->delivery_status = $status;
            }
            
            // Save the sale with the correct partition
            $sale->save();

            // Update product inventory if needed
            foreach ($products as $product) {
                $seller_id = $product->pivot->seller_id;
                $update_statuses = ['Delivered', 'Returned', 'Undispatched', 'In Transit'];
                
                if (!$product->virtual && in_array($status, $update_statuses)) {
                    if ($product->stock_management == 1) {
                        if ($status == 'Awaiting Dispatch' || $status == 'In Transit' || 
                            ($old_status == 'In Transit' && in_array($status, ['Undispatched', 'Returned', 'Delivered']))) {
                            
                            $productInventory = ProductInventory::where('warehouse_id', $data['warehouse_id'])
                                ->where('seller_id', $seller_id)
                                ->where('product_id', $product->id)
                                ->first();

                            if ($productInventory) {
                                $productInventory->updateInventory($status, $product->pivot->quantity, $product->id, $this->id, $old_status);
                            }
                        }
                    } elseif ($product->stock_management == 2) {
                        if ($status == 'In Transit' || 
                            ($old_status == 'In Transit' && in_array($status, ['Undispatched', 'Returned', 'Delivered']))) {
                            
                            $bins = Bin::where('warehouse_id', $data['warehouse_id'])->pluck('id');
                            $productBin = ProductBin::whereIn('bin_id', $bins)
                                ->where('product_id', $product->id)
                                ->first();

                            if ($productBin) {
                                $productBin->updateInventory($status, $product->pivot->quantity, $product->id, $this->id, $old_status);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return $sale;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateSaleZone($data)
    {
        $zoneFrom = array_key_exists('zone_from', $data) ? $data['zone_from'] : 1;
        $zoneTo = array_key_exists('zone_to', $data) ? $data['zone_to'] : null;

        // Check if 'zone_to' is present
        if (is_null($zoneTo)) {
            // Log::emergency('Zone To data missing', ['data' => $data, 'id' => $this->id, 'user' => $this->user]);
        }

        // Use updateOrCreate with conditional update
        $saleZone = SaleZone::updateOrCreate(
            [
                'sale_id' => $this->id,
            ],
            [
                'zone_id' => $zoneFrom,
                'dispatch_date' => now(),
            ]
        );

        // Conditionally update 'zone_to' if it exists
        if (!is_null($zoneTo)) {
            $saleZone->zone_to = $zoneTo;
            $saleZone->save();
        }
    }
}
