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
        /*return [
            'product_id'            => $this->id,
            'name'                  => $this->name,
            'location_name'         => $this->location_name,
            'location_id'           => $this->location_id,
            'qty'                   => $this->available,
            'on_hand'               => $this->on_hand,
            'sku'                   => $this->sku,
            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'category_id'           => $this->category_id,
            'category_name'         => optional($this->category)->name
        ];*/

        return [
            'id'            => $this->product_id,
            'product_id'            => $this->id,
            'product_name'          => optional($this->product)->name,
            'location_name'         => optional($this->location)->name,
            'location_id'           => $this->location_id,
            'qty'                   => $this->available,
            'on_hand'               => $this->on_hand,
            'sku'                   => optional($this->product)->sku,
            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'category_id'           => $this->category_id,
            'category_name'         => optional($this->category)->name
        ];
    }
}
