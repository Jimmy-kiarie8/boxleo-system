<?php

namespace App\Jobs;

use App\Models\OrderHistory;
use App\Models\ProductInventory;
use App\Models\ReturnSale;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Rider;
use App\Scopes\OrderScope;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkUpdateJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zone_from, $zone_to, $ids, $warehouseId, $user, $status, $rider_id, $courier_id;

    public function __construct($zone_from, $zone_to, $ids, $warehouseId, $user, $status, $rider_id, $courier=null)
    {
        $this->zone_from = $zone_from;
        $this->zone_to = $zone_to;
        $this->ids = $ids;
        $this->warehouseId = $warehouseId;
        $this->user = $user;
        $this->status = $status;
        $this->rider_id = $rider_id;
        $this->courier_id = $courier;
    }

    public function handle()
    {


        $rider = Rider::find($this->rider_id);
        // Start a transaction
        DB::beginTransaction();

        // if($this->status === 'Dispatched') {
        $status = ($this->zone_to == 1 || $this->zone_to == 13 || $this->zone_to == 92 || $this->zone_to == 55) ? 'Dispatched' : 'In Transit';
        // } else {
        //     $status = $this->status;
        // }
        $validStatuses = ['Awaiting Dispatch', 'Reschedule'];
        // $validStatuses = ['Awaiting Dispatch', 'Undispatched', 'Reschedule'];


        try {
            // Step 1: Fetch and validate orders
            $orders = Sale::whereIn('id', $this->ids)
                ->whereIn('delivery_status', $validStatuses)
                ->get();

            if ($orders->isEmpty()) {
                throw new Exception('No valid orders found for dispatch.');
            }
            // Step 2: Bulk update orders to dispatched
            Sale::whereIn('id', $orders->pluck('id'))
                ->update(['delivery_status' => $status, 'dispatched_on' => now(), 'status' => 'Shipped']);

            if ($status === 'Dispatched' || $status === 'In Transit') {
                $rider_name = ($rider) ? $rider->name : '';
                $history = 'Order dispatched to ' . $rider_name . ' by ' . $this->user->name;
            } else {
                $history = 'Order ' . $status . ' by ' . $this->user->name;
            }

            $orderHistory = [];

            foreach ($orders->pluck('id') as $his) {
                $orderHis = [
                    'action' => 'Order updated',
                    'comment' => '',
                    'update_comment' => $history,
                    'user_name' => $this->user->name,
                    'tracking_comment' => 'Order ' . $status,
                    'user_id' => ($this->user) ? $this->user->id : 1,
                    'sale_id' => $his,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $orderHistory[] = $orderHis;

                $sale = Sale::find($his);
                $sale->rider_id = $this->rider_id;
                $sale->zone_id = $this->zone_to;
                if($this->courier_id) {
                    $sale->courier_id = $this->courier_id;
                }
                $sale->save();
            }

            OrderHistory::insert($orderHistory);


            // Step 3: Update related tables
            foreach ($orders as $order) {
                // Update SaleZone
                $this->updateSaleZone([
                    'zone_from' => $this->zone_from,
                    'zone_to' => $this->zone_to,
                ], $order->id);

                // Update RiderSale
                if (isset($this->rider_id)) {
                    $this->updateRiderSale($this->rider_id, $order->id);
                }


                // Update Inventory
                $this->updateInventory($order, $this->warehouseId);
            }

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Orders dispatched successfully.']);
        } catch (Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function updateSaleZone($data, $orderId)
    {
        $zoneFrom = $data['zone_from'] ?? 1;
        $zoneTo = $data['zone_to'] ?? null;

        // Use updateOrCreate with conditional update
        $saleZone = SaleZone::updateOrCreate(
            [
                'sale_id' => $orderId,
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

    public function updateRiderSale($riderId, $orderId)
    {
        $riderSale = new RiderSale();
        $riderSale->create($orderId, $riderId);

    }

    public function updateInventory($order, $warehouseId)
    {
        $products = $order->products;
        $status = 'In Transit'; // Or whatever status is relevant
        $oldStatus = $order->getOriginal('delivery_status');


        foreach ($products as $product) {
            $sellerId = $product->pivot->seller_id;
            $updateStatuses = ['Delivered', 'Returned', 'Undispatched', 'In Transit'];

            if (!$product->virtual && in_array($status, $updateStatuses)) {

                if ($product->stock_management == 1) {
                    $productInventory = ProductInventory::where('warehouse_id', $warehouseId)
                        ->where('seller_id', $sellerId)
                        ->where('product_id', $product->id)
                        ->first();

                    if ($productInventory) {
                        $productInventory->updateInventory($status, $product->pivot->quantity, $product->id, $order->id, $oldStatus);
                    }
                } elseif ($product->stock_management == 2) {
                    $bins = Bin::where('warehouse_id', $warehouseId)->get('id');
                    $binIds = $bins->pluck('id')->toArray();

                    $productBin = ProductBin::whereIn('bin_id', $binIds)->where('product_id', $product->id)->first();

                    if ($productBin) {
                        $productBin->updateInventory($status, $product->pivot->quantity, $product->id, $order->id, $oldStatus);
                    }
                }
            }
        }
    }
}
