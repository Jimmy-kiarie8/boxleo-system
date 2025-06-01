<?php

namespace App\Services;

class ProductService
{
    
    public function transformDisplayProduct($products)
    {
        return $products->through(function ($product) {
            $stockInfo = $this->calculateStockInfo($product);
            $priceInfo = $this->calculatePriceInfo($product->skus);

            return array_merge($product->toArray(), $stockInfo, $priceInfo, [
                'variants' => $product->skus->count(),
                'discount' => 0,
            ]);
        });
    }

    public function calculateStockInfo($product)
    {
        $stockInfo = [
            'onhand' => 0,
            'delivered' => 0,
            'available_for_sale' => 0,
            'commited' => 0,
        ];

        if ($product->stock_management == 1 && $product->bins) {
            $stockInfo = $this->sumStockInfo($product->inventory);
        } elseif ($product->stock_management == 2 && $product->bins) {
            $stockInfo = $this->sumStockInfo($product->bins->pluck('pivot'));
        }

        return $stockInfo;
    }

    public function sumStockInfo($items)
    {
        return $items->reduce(function ($carry, $item) {
            $carry['onhand'] += $item['onhand'];
            $carry['delivered'] += $item['delivered'];
            $carry['commited'] += $item['commited'];
            $carry['available_for_sale'] += $item['available_for_sale'];
            return $carry;
        }, [
            'onhand' => 0,
            'delivered' => 0,
            'commited' => 0,
            'available_for_sale' => 0,
        ]);
    }

    public function calculatePriceInfo($skus)
    {
        if ($skus->isEmpty()) {
            return ['price' => 0, 'quantity' => 0];
        }

        $prices = $skus->pluck('price');
        $quantity = $skus->sum('quantity');

        if ($skus->count() > 1) {
            $price = 'from ' . $prices->min();
        } else {
            $price = $prices->first();
        }

        return ['price' => $price, 'quantity' => $quantity];
    }
}
