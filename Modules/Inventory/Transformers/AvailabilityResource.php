<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class AvailabilityResource extends ResourceService
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
            'id'                    => $this->product_id,
            'product_id'            => $this->id,
            'product_name'          => optional($this->product)->getConcatNameAttribute(),
            'in_transit_suppliers'  => optional($this->product)->getInTransitAttribute($this->product_id),
            'in_transit_transfers'  => optional($this->product)->getInTransitTransferAttribute($this->product_id),
            'location_name'         => optional($this->location)->name,
            'location_id'           => $this->location_id,
            'qty'                   => ($this->on_hand - $this->allocated),
            'on_hand'               => $this->on_hand,
            'on_order'              => $this->on_order,
            'allocated'             => $this->allocated,
            'sku'                   => optional($this->product)->sku,
            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'category_id'           => $this->category_id,
            'category_name'         => optional($this->category)->name,
            'total'                 => $this->count()
        ];
    }
}