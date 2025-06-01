<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'price' => $product->price,
                    'onhand' => $product->onhand,
                    'commited' => $product->commited,
                    'available_for_sale' => $product->available_for_sale,
                    'sku_no' => $product->sku_no,
                    'images' => $product->images,
                    'created_at' => $product->created_at
                ];
            }),
        ];
    }
}
