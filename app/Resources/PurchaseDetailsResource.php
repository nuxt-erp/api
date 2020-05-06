<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailsResource extends JsonResource
{
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
            'gross_total'       => $this->gross_total,
            'total'             => $this->total,
            'estimated_date'    => $this->estimated_date,
            'qty_received'      => $this->qty_received,
            'received_date'     => $this->received_date,
            'ref'               => $this->ref,
            'item_status'       => $this->item_status,
            'can_be_deleted'    => true
        ];
    }
}
