<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Log::debug($this->id);
        // return parent::toArray($request);
        return [
            'action' => $this->action,
            'status' => $this->tracking_comment,
            'comment' => $this->comment,
            'created_at' => Carbon::parse($this->created_at)->toISOString(),
            'sale_id' => $this->sale_id,
            'updated_fields' => $this->updated_fields
        ];
    }
}
