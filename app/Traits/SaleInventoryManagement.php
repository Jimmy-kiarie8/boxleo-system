<?php

namespace App\Traits;

use App\Models\DailyStats;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductReceive;
use App\Models\ProductSale;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait SaleInventoryManagement
{
    public function updateInventory($status, $quantity, $product_id, $sale_id, $old_status)
    {
        // $daily_start = new DailyStats();

        switch ($status) {
            case 'Awaiting Dispatch':
                // $this->reduceAvailableForSale($quantity);
                $this->increaseCommitted($quantity);
                // $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                break;

            case 'In Transit':
                $this->reduceAvailableForSale($quantity);
                // $this->increaseCommitted($quantity);
                $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                break;
            case 'Delivered':

                $this->increaseDelivered($quantity);
                // $this->reduceOnHand($quantity);
                $this->reduceCommitted($quantity, $old_status, $status);
                $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                // $daily_start->delivered_count($quantity, $product_id);
                break;
            case 'Returned':
                $this->increaseAvailableForSale($quantity, $old_status, $status);
                $this->reduceCommitted($quantity, $old_status, $status);
                $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                // $daily_start->returned_count($quantity, $product_id);
                break;
            case 'Return Received':
                $this->increaseAvailableForSale($quantity, $old_status, $status);
                $this->reduceCommitted($quantity, $old_status, $status);
                $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                // $daily_start->returned_count($quantity, $product_id);
                break;
            case 'Refund':
                $this->increaseAvailableForSale($quantity, $old_status, $status);
                $this->reduceDelivered($quantity, $old_status, $status);
                // $this->increaseOnHand($quantity);
                break;
                // case 'Undispatched':
                //     $this->increaseAvailableForSale($quantity, $old_status, $status);
                //     $this->reduceCommitted($quantity, $old_status, $status);
                //     $this->updateProductSale($product_id, $sale_id, $quantity, $status);
                //     break;
            default:
                // Handle unsupported status
                break;
        }
        $this->updateDailyStats($status, $quantity, $product_id);

        $user = User::first();
        $this->recordTransaction([
            'ou_id' => 1,
            'user_id' => $user->id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'transaction_type' => $status,
            'comment' => "Stock level set to {$quantity} by {$user->name}"
        ]);
    }

    public function adjustment($product_id, $onhand, $commited, $available, $delivered, $old, $adj_qty)
    {
        // $this->onhand = $onhand;
        $this->commited = $commited;
        $this->delivered = $delivered;
        $this->available_for_sale = $available;
        $this->save();

        $changes = 'Prev onhand: ' . $old['onhand'] . '. Current onhand: ' . $onhand . ', Prev available: ' . $old['available'] . '. Current available: ' . $available . ', Prev commited: ' . $old['commited'] . '. Current commited:' . $commited . '. Current delivered:' . $delivered;

        $product = Product::find($product_id);
        $product->update_comment = $product->product_name . ' quatity adjusted by ' . '<b style="color: red;">' . Auth::user()->name  . '</b>. ' . $changes;
        $product->save();

        ProductReceive::create([
            'reference' => make_reference_id('PRC', ProductReceive::max('id') + 1),
            'quantity' => $adj_qty,
            'product_id' => $product_id,
            'type' => 'Adjustment',
        ]);
    }

    public function warehouseReceive($product_id, $quantity)
    {
        $this->onhand += $quantity;
        $this->available_for_sale += $quantity;
        $this->save();

        $product = Product::find($product_id);
        $product->update_comment = $quantity . ' products received by ' . '<b style="color: red;">' . Auth::user()->name  . '</b>';
        $product->save();

        ProductReceive::create([
            'reference' => make_reference_id('PRC', ProductReceive::max('id') + 1),
            'quantity' => $quantity,
            'product_id' => $product_id,
            'type' => 'Receive',
        ]);
    }

    public function transferSend($product_id, $quantity)
    {
        // $this->onhand -= $quantity;
        $this->available_for_sale -= $quantity;
        $this->save();

        $product = Product::find($product_id);
        $product->update_comment = $quantity . ' products transfered by ' . '<b style="color: red;">' . Auth::user()->name  . '</b>';
        $product->save();

        $user = Auth::user();
        $this->recordTransaction([
            'ou_id' => $user->ou_id,
            'user_id' => $user->id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'transaction_type' => 'Transfer Out',
            'comment' => "{$quantity} transfered by {$user->name}"
        ]);
    }

    public function transferReceive($product_id, $quantity)
    {
        $this->onhand += $quantity;
        $this->available_for_sale += $quantity;
        $this->save();

        $product = Product::find($product_id);
        $product->update_comment = $quantity . ' products transfered by ' . '<b style="color: red;">' . Auth::user()->name  . '</b>';
        $product->save();

        $user = Auth::user();
        $this->recordTransaction([
            'ou_id' => $user->ou_id,
            'user_id' => $user->id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'transaction_type' => 'Transfer In',
            'comment' => "{$quantity} received by {$user->name}"
        ]);
    }


    public function createBin($bin, $product_id, $qty, $warehouse_id)
    {
        $bin_product = new ProductBin();
        $bin_product->product_id =  $product_id;
        $bin_product->bin_id = $bin;
        $bin_product->onhand = $qty;
        $bin_product->available_for_sale = $qty;
        $bin_product->warehouse_id = $warehouse_id;
        $bin_product->quantity = $qty;
        $bin_product->save();
    }

    private function reduceAvailableForSale($quantity)
    {
        if ($this->available_for_sale > 0) {
            $this->available_for_sale -= $quantity;
            $this->save();
        }
    }

    private function increaseAvailableForSale($quantity, $old_status, $status)
    {
        if ($old_status == 'Return Received' && $status == 'Returned') {
            return;
        }
        $this->available_for_sale += $quantity;
        $this->save();
    }

    private function increaseCommitted($quantity)
    {
        $this->commited += $quantity;
        $this->save();
    }

    private function reduceOnHand($quantity)
    {
        if ($this->onhand > 0) {
            $this->onhand -= $quantity;
            $this->save();
        }
    }

    private function reduceCommitted($quantity, $old_status, $status)
    {
        if ($this->commited > 0) {
            if ($old_status == 'Return Received' && $status == 'Returned') {
                return;
            }
            $this->commited -= $quantity;
            $this->save();
        }
    }

    private function increaseOnHand($quantity)
    {
        $this->onhand += $quantity;
        $this->save();
    }
    private function increaseDelivered($quantity)
    {
        $this->delivered += $quantity;

        $this->save();

    }

    private function reduceDelivered($quantity)
    {
        if ($this->delivered > 0) {
            $this->delivered -= $quantity;
            $this->save();
        }
    }

    public function merchantTransfer($quantity, $bin_from, $bin_to, $product_id, $productId)
    {
        $bin_from = ProductBin::where('id', $bin_from)->where('product_id', $product_id)->first();
        if (!$bin_from) {
            abort(422, "Product doesn't exist in this bin");
        }
        $bin_to = ProductBin::where('id', $bin_to)->where('product_id', $productId)->first();

        if ($bin_to) {
            abort(422, "Product doesn't exist in this bin");
        }

        $bin_from->available_for_sale -= $quantity;
        $bin_from->onhand -= $quantity;
        $bin_from->save();

        $bin_to->available_for_sale += $quantity;
        $bin_to->onhand += $quantity;
        $bin_to->save();
    }

    public function updateProductSale($product_id, $sale_id, $quantity, $status)
    {
        $product_sale = ProductSale::where('product_id', $product_id)->where('sale_id', $sale_id)->first();

        if ($product_sale) {
            if ($status == 'In Transit') {
                $product_sale->update(['quantity_sent' => $quantity, 'sent' => true]);
            } elseif ($status == 'Delivered') {
                $product_sale->update(['quantity_delivered' => $quantity, 'delivered' => true]);
            } elseif ($status == 'Returned') {
                $product_sale->update(['quantity_returned' => $quantity, 'returned' => true]);
            }
        }
    }

    public function recordOpeningStats($product_id, $ou_id, $type)
    {
        $today = Carbon::today();

        if ($type == 'Opening') {
            DailyStats::firstOrNew([
                'date' => $today,
                'product_id' => $product_id,
                'ou_id' => $ou_id,
                'starting_count' => $this->getProductQuantity($product_id, $ou_id)
            ]);
        } else {
            $dailyStats = DailyStats::where('created_at', $today)->where('product_id', $product_id)->where('ou_id', $ou_id);
            $dailyStats->closing_count = $this->getProductQuantity($product_id, $ou_id);
            $dailyStats->save();
        }
    }


    private function updateDailyStats($status, $quantity, $product_id)
    {
        $today = Carbon::today();
        $dailyStats = DailyStats::firstOrNew([
            'date' => $today,
            'product_id' => $product_id,
            'ou_id' => (Auth::check()) ? Auth::user()->ou_id : 1
        ]);

        // Update the specific status count
        switch ($status) {
            case 'Delivered':
                $dailyStats->delivered += $quantity;
                break;
            case 'In Transit':
                $dailyStats->dispatched += $quantity;
                break;
            case 'Returned':
                $dailyStats->returned += $quantity;
                break;
            case 'Scheduled':
                $dailyStats->scheduled += $quantity;
                break;
            case 'Uploaded':
                $dailyStats->uploaded += $quantity;
                break;
            case 'Pending':
                $dailyStats->pending += $quantity;
                break;
                // Add cases for other statuses as needed
        }

        $dailyStats->save();
    }

    public function getProductQuantity($product_id, $ou_id)
    {
        $warehouse = Warehouse::where('ou_id', $ou_id)->first();
        $warehouse_id = $warehouse->id;
        $product = Product::find($product_id);
        if ($product->stock_management == 1) {
            return ProductInventory::where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)->sum('available_for_sale');
        } elseif ($product->stock_management == 2) {
            $ids = Bin::where('warehouse_id', $warehouse_id)->pluck('id')->toArray();
            return ProductBin::whereIn('bin_id', $ids)->where('product_id', $product->id)->sum('available_for_sale');
        }
    }

    /**
     * Record a transaction in the inventory_transactions table.
     *
     * @param array $data
     * @return InventoryTransaction
     */
    public function recordTransaction(array $data): StockMovement
    {
        $data['user_id'] = Auth::id();
        return StockMovement::create($data);
    }
}
