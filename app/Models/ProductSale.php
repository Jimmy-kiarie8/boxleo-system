<?php

namespace App\Models;

use App\Seller;
use App\Traits\SaleInventoryManagement;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use SaleInventoryManagement;
    protected $table = 'product_sale';

    protected $fillable = ['sale_id', 'product_id', 'seller_id', 'sku_id', 'sku_no', 'price', 'quantity', 'quantity_tobe_delivered', 'total_price', 'quantity_sent', 'quantity_returned', 'delivered', 'returned', 'quantity_delivered', 'quantity_remaining', 'sent'];

    public function seller()
    {
        return $this->hasOne(Seller::class);
    }
    // public function returns()
    // {
    //     return $this->hasMany(ReturnSale::class, 'product_sale_id');
    // }

    public function returns()
    {
        return $this->belongsToMany(ReturnSale::class);
    }

    public function product_sales($id)
    {
        return ProductSale::where('product_id', $id)->get()->pluck(['sale_id']);
    }

    public function getUndeliveredAttribution($sale)
    {
        return 300;
    }

    // ProductSale.php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
