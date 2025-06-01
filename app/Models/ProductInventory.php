<?php

namespace App\Models;

use App\Models\Warehouse\Warehouse;
use App\Traits\SaleInventoryManagement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInventory extends Pivot
{
    use HasFactory, SoftDeletes;
    use SaleInventoryManagement;
    
    protected $table = 'product_inventories';
    
    protected $fillable = ['user_id', 'warehouse_id', 'product_id', 'seller_id', 'onhand', 'commited', 'available_for_sale', 'delivered'];

    // public $with = ['products', 'warehouses'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}
