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
            'id'                       => $this->id,
            'receiving_id'             => $this->receiving_id,
            'product_id'               => $this->product_id,
            'product_barcode'          => optional($this->product)->barcode ?? null,
            'product_carton_barcode'   => optional($this->product)->carton_barcode ?? null,
            'product_sku'              => optional($this->product)->sku ?? null,
            'product_name'             => $this->product->sku .' - '.$this->product->name,
            'searchable'               => optional($this->product)->barcode ?? optional($this->product)->sku,
            'product_full_name'        => optional($this->product)->getDetailsAttributeValue(),
            'product_display_name'     => optional($this->product)->getDetailsAttributeValue(),
            'original_qty_allocated'   => $this->qty_allocated,
            'qty_allocated'            => $this->qty_allocated,
            'qty_received'             => $this->qty_received,
            'received_date'            => $this->received_date,
            'item_status'              => $this->item_status,
            'in_purchase'              => false,
            'created_at'               => optional($this->created_at)->format('Y-m-d'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d'),
        ];
    }
}
