<?php

namespace Modules\Purchase\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
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
            'id'                => $this->id,
            'purchase_id'       => $this->purchase_id,
            'product_id'        => $this->product_id,
            'product_name'      => optional($this->product)->name,
            'name'              => optional($this->product)->sku . ' - ' . optional($this->product)->name,
            'qty'               => $this->qty,
            'price'             => $this->price,
            'sub_total'         => $this->gross_total,
            'total'             => $this->total,
            'taxes'             => $this->taxes,
            'discounts'         => $this->discounts,
            'estimated_date'    => $this->estimated_date,
            'qty_received'      => $this->qty_received,
            'received_date'     => $this->received_date,
            'ref'               => $this->ref,
            'item_status'       => $this->item_status,
            'can_be_deleted'    => true
        ];
    }
}
