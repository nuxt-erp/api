<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ReceivingDetailResource extends ResourceService
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
            'receiving_id'          => $this->receiving_id,
            'product_id'            => $this->product_id,
            'product_sku'           => optional($this->product)->sku,
            'product_name'          => optional($this->product)->name,
            'searchable'            => optional($this->product)->barcode ?? optional($this->product)->sku,
            'product_full_name'     => $this->product ? $this->product->sku . ' - ' . $this->product->name : null,
            'original_qty_allocated'=> $this->qty_allocated,
            'qty_allocated'         => $this->qty_allocated,
            'qty_received'          => $this->qty_received,
            'received_date'         => $this->received_date,
            'item_status'           => $this->item_status,
            'created_at'            => optional($this->created_at)->format('Y-m-d'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d'),
        ];
    }
}
