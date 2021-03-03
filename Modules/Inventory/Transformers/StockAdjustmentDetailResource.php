<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockAdjustmentDetailResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'stock_adjustment_id'   => $this->stock_adjustment_id,
            'product_id'            => $this->product_id,
            'product_name'          => optional($this->product)->name,
            'sku'                   => optional($this->product)->sku,
            'location_id'           => $this->location_id,
            'location_name'         => optional($this->location)->name,
            'bin_id'                => $this->bin_id,
            'bin_name'              => optional($this->bin)->name,
            'qty'                   => $this->qty,
            'adjustment_type'       => $this->adjustment_type,
            'on_hand'               => $this->stock_on_hand,
            'variance'              => $this->variance,
            'status'                => $this->status,
            'notes'                 => $this->notes,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
