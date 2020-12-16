<?php

namespace Modules\Purchase\Transformers;

use App\Resources\ResourceService;

class PurchaseResource extends ResourceService
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
            'po_number'         => $this->po_number,
            'name'              => $this->po_number,
            'ref_code'          => $this->ref_code,
            'purchase_date'     => optional($this->purchase_date)->format('Y-m-d'),
            'supplier_id'       => $this->supplier_id,
            'supplier_name'     => optional($this->supplier)->name,
            'status'            => $this->status,
            'status_label'      => ($this->status == 1 ? "Received" : "In progress"),
            'invoice_number'    => $this->invoice_number,
            'tracking_number'   => $this->tracking_number,
            'notes'             => $this->notes,
            'total'             => $this->total,
            'subtotal'          => $this->subtotal,
            'taxes'             => $this->taxes,
            'discount'          => $this->discount,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'eta'               => $this->getEarliestEtaAttribute(),
            'can_be_deleted'    => true
        ];

    }
}
