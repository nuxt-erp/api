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
            'id'                     => $this->id,
            'po_number'              => $this->po_number,
            'name'                   => $this->po_number ,
            'ref_code'               => $this->ref_code,
            'iteration'              => $this->iteration,
            'purchase_date'          => optional($this->purchase_date)->format('Y-m-d H:i:s'),
            'tracking_numbers'       => optional($this->tracking_numbers)->toArray(),
            'tracking_numbers_name'  => optional(optional($this->tracking_numbers)->pluck('tracking_number'))->toArray(),
            'invoice_date'           => optional($this->invoice_date)->format('Y-m-d H:i:s'),
            'supplier_id'            => $this->supplier_id,
            'supplier_name'          => optional($this->supplier)->name,
            'status_id'              => $this->getStatusId($this->status),
            'status'                 => $this->status,
            'invoice_number'         => $this->invoice_number,
            'notes'                  => $this->notes,
            'total'                  => $this->total,
            'subtotal'               => $this->subtotal,
            'taxes'                  => $this->taxes,
            'discount'               => $this->discount ?? 0,
            'location_id'            => $this->location_id,
            'location_name'          => optional($this->location)->name,
            'eta'                    => $this->getEarliestEtaAttribute(),
            'can_be_deleted'         => true
        ];
    }
}
