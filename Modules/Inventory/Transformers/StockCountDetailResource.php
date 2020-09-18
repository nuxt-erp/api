<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockCountDetailResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'stockcount_id'    => $this->stockcount_id,
            'product_id'      => $this->product_id,
            'product_name'    => optional($this->product)->getFullDescriptionAttribute(),
            'sku'             => optional($this->product)->sku,
            'qty'             => $this->qty,
            'on_hand'         => $this->stock_on_hand,
            'variance'        => $this->variance,
            'notes'           => $this->notes,
            'brand_id'        => optional($this->product)->brand_id,
            'brand_name'      => optional($this->product)->brand->name,
            'category_id'     => optional($this->product)->category_id,
            'category_name'   => optional($this->product)->category->name,
            'location_id'     => $this->location_id,
            'location_name'   => optional($this->location)->name
        ];
    }
}
