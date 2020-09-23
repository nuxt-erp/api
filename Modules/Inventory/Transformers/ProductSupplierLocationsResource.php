<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductSupplierLocationsResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'product_supplier_id'  => $this->product_supplier_id,
            'location_id'          => $this->location_id,
            'location_name'        => optional($this->location)->name,
            'lead_time'            => $this->lead_time,
            'safe_stock'           => $this->safe_stock,
            'reorder_qty'          => $this->reorder_qty,
            'created_at'           => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'           => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
