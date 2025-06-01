<?php

namespace App\Models\Warehouse;

use App\Scopes\BinScope;
use App\Traits\SaleInventoryManagement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBin extends Model
{
    use HasFactory;
    use SaleInventoryManagement;

    protected $fillable = ['product_id', 'warehouse_id', 'bin_id', 'quantity', 'onhand', 'commited', 'available_for_sale', 'delivered', 'bin_id', 'product_id', 'warehouse_id'];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }


    public function increaseStock($quantity)
    {
        $this->quantity += $quantity;
        $this->save();
    }

    public function reduceStock($quantity)
    {
        $this->quantity -= $quantity;
        $this->save();
    }
}
