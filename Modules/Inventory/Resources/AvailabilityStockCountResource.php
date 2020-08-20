<?php

namespace Modules\Inventory\Resources;

use App\Resources\ResourceService;

class AvailabilityStockCountResource extends ResourceService
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
            'product_id'            => $this->id,
            'name'                  => $this->name,
            'location_name'         => $this->location_name,
            'location_id'           => $this->location_id,
            'qty'                   => $this->available,
            'on_hand'               => $this->on_hand,
            'sku'                   => $this->sku,
            'brand_id'              => $this->brand_id,
            'brand_name'            => $this->brand_name,
            'category_id'           => $this->category_id,
            'category_name'         => $this->category_name,
            'total'                 => $this->count()
        ];

    }
}
