<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTakeDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'stocktake_id'    => $this->stocktake_id,
            'product_id'      => $this->product_id,
            'name'            => optional($this->product)->getConcatNameAttribute(),
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
            'location_name'   => optional($this->location)->name,
            'can_be_deleted'  => true
        ];
    }
}
