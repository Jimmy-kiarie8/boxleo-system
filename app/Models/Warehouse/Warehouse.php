<?php

namespace App\Models\Warehouse;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Sale;
use App\Models\ShipmentRequest;
use App\Models\Transfer;
use App\Models\User;
use App\Scopes\WarehouseScope;
use App\Traits\InventoryWarehouseManagement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    use InventoryWarehouseManagement;

    // public $with = ['areas'];

    public function saleorder()
    {
        return $this->hasMany(Sale::class, 'warehouse_id');
    }
    public function inventory()
    {
        return $this->hasMany(ProductInventory::class, 'warehouse_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_warehouses');
    }

    /**
     * Get all of the transfers for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfer(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_warehouse_id');
    }

    public function transferFrom(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_warehouse_id');
    }
    
   /*  public function productinv()
    {
        return $this->belongsToMany(Product::class, 'product_inventories');
    } */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product_warehouse()
    {
        return $this->hasMany(Product_warehouse::class, 'warehouse_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
    /**
     * Get all of the areas for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class, 'warehouse_id');
    }

    /**
     * Get all of the shipments for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(ShipmentRequest::class, 'warehouse_id');
    }


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope(new WarehouseScope);
    // }
}
