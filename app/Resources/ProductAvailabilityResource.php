<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAvailabilityResource extends JsonResource
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
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'product_name'          => $this->product->name,
            'company_id'            => $this->company_id,
            'company_name'          => optional($this->company)->name,
            'location_id'           => $this->location_id,
            'location_name'         => optional($this->location)->name,
            'available'             => $this->available,
            'on_hand'               => $this->on_hand
        ];
    }
}
