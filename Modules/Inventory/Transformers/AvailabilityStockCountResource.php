<?php

namespace Modules\Inventory\Transformers;

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
            'location_name'         => $this->location_name2 ?? $this->location_name,
            'location_id'           => $this->location_id2 ?? $this->location_id,
            'qty'                   => $this->available,
            'on_hand'               => $this->on_hand,
            'sku'                   => $this->sku,
            // usually we filter by location, so will be the qty of the location, the sum just guarantee the information makes sense even you don't inform a location to filter
            'qty'                   => optional($this->availabilities)->sum('available') ?? 0,
            'on_hand'               => optional($this->availabilities)->sum('on_hand') ?? 0,
            // this logic bring the location if is filtered and exist or is not filtered
            'location_id'           => optional($this->availabilities->first())->location_id,
            'location_name'         => $this->availabilities->first() ? optional($this->availabilities->first()->location)->name : null,

            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'category_id'           => $this->category_id,
            'category_name'         => optional($this->category)->name,
            'total'                 => $this->count()
        ];

    }
}
