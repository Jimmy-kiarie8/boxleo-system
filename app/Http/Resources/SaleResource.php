<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'total_price' => $this->total_price,
            // 'sub_total' => $this->sub_total,
            // 'sku_no' => $this->sku_no,
            'customer_notes' => $this->customer_notes,
            // 'discount' => $this->discount,
            'shipping_charges' => $this->shipping_charges,
            'delivery_date' => $this->delivery_date,
            'delivered_on' => $this->delivered_on,
            'status' => $this->status,
            'delivery_status' => $this->delivery_status,
            // 'warehouse_id' => $this->warehouse_id,
            // 'payment_id' => $this->payment_id,
            // 'paypal' => $this->paypal,
            'payment_method' => $this->payment_method,
            // 'payment_id' => $this->payment_id,
            // 'terms' => $this->terms,
            'mpesa_code' => $this->mpesa_code,
            'invoiced' => $this->invoiced,
            'paid' => $this->paid,
            'product_name' => $this->product_name,
            'created_at' => $this->created_at,
            'pickup_address' => $this->pickup_address,
            'pickup_phone' => $this->pickup_phone,
            'pickup_shop' => $this->pickup_shop,
            'pickup_city' => $this->pickup_city,
            // 'is_return_waiting_for_approval' => $this->is_return_waiting_for_approval,
            // 'is_salesreturn_allowed' => $this->is_salesreturn_allowed,
            // 'is_test_order' => $this->is_test_order,
            // 'is_emailed' => $this->is_emailed,
            // 'is_dropshipped' => $this->is_dropshipped,
            // 'is_cancel_item_waiting_for_approval' => $this->is_cancel_item_waiting_for_approval,
            // 'track_inventory' => $this->track_inventory,
            'confirmed' => $this->confirmed,
            'client' => $this->whenLoaded('client'),
            'products' => $this->whenLoaded('products'),
            'history' => $this->whenLoaded('orderHistory'),
            'seller' => $this->whenLoaded('seller'),

            'products_count' => $this->products_count,
        ];
    }
}
