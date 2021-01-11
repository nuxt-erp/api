<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockCountDetailResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'stockcount_id'     => $this->stockcount_id,
            'product_id'        => $this->product_id,
            'product_name'      => $this->product->sku .' - '.$this->product->name,
            'product_sku'       => $this->product->sku,
            'product_brand'     => optional($this->product->brand)->name,
            'product_category'  => optional($this->product->category)->name,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'bin_id'            => $this->bin_id,
            'bin_name'          => optional($this->bin)->name,
            'bin_searchable'    => optional($this->bin)->barcode,
            'searchable'        => optional($this->product)->barcode ?? optional($this->product)->sku, 
            'on_hand'           => $this->on_hand ?? 0,
            'available'         => 0, //@todo we are not using available rn
            'qty'               => $this->qty,
            'variance'          => $this->variance,
            'notes'             => $this->notes
        ];
    }
}
