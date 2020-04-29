<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'ref_code'          => $this->ref_code,
            'purchase_date'     => $this->purchase_date,
            'company_id'        => $this->company_id,
            'supplier_id'       => $this->supplier_id,
            'supplier_name'     => optional($this->supplier)->name,
            'status'            => $this->status,
            'tracking_number'   => $this->tracking_number,
            'invoice_number'    => $this->invoice_number,
            'notes'             => $this->notes,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'can_be_deleted'    => true
        ];
    }
}
