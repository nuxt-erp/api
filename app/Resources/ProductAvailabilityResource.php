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
            'warehouse_id'          => $this->warehouse_id,
            'warehouse_name'        => optional($this->warehouse)->name,
            'location_id'           => $this->location_id,
            'location_name'         => optional($this->location)->name,
            'available_quantity'    => $this->available_quantity,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
