<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductReorderLevelResource extends ResourceService
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
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'product_name'         => optional($this->product)->name,
            'location_id'           => $this->location_id,
            'location_name'         => optional($this->location)->name,
            'safe_stock'            => $this->safe_stock,
            'reorder_qty'           => $this->reorder_qty,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
