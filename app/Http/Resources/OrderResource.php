<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'total_price' => $this->total_price,
            'customer_notes' => $this->customer_notes,
            'delivery_date' => Carbon::parse($this->delivery_date)->toISOString(),
            'delivered_on' => Carbon::parse($this->delivered_on)->toISOString(),
            // 'status' => $this->status,
            'shipping_charges' => $this->shipping_charges,
            'delivery_status' => $this->delivery_status,
            'payment_method' => $this->payment_method,
            'invoiced' => $this->invoiced,
            'paid' => $this->paid,
            'confirmed' => $this->confirmed,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'customer' => new ClientResource($this->whenLoaded('client')),
            'order_updates' => OrderHistoryResource::collection($this->whenLoaded('orderHistory')),
            'line_items' => ProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
