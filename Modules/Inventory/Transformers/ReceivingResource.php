<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ReceivingResource extends ResourceService
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
            'name'              => $this->name,
            'po_number'         => $this->po_number,
            'supplier_id'       => $this->supplier_id,
            'supplier_name'     => optional($this->supplier)->name,
            'status'            => $this->status,
            'allocation_status' => $this->allocation_status,
            'invoice_number'    => $this->invoice_number,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'receiving_details' => ReceivingDetailResource::collection($this->details),
            'created_at'        => optional($this->created_at)->format('Y-m-d'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d'),
        ];
    }
}
