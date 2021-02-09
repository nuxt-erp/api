<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;
use Modules\Inventory\Entities\Receiving;

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
            'is_allocated'      => $this->allocation_status === Receiving::ALLOCATED,
            'invoice_number'    => $this->invoice_number,
            'tracking_number'   => $this->tracking_number,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'receiving_details' => ReceivingDetailResource::collection($this->details),
            'received_date'     => optional($this->received_date)->format('Y-m-d'),
            'author_id'         => $this->author_id,
            'author_name'       => optional($this->author)->name,
            'created_at'        => optional($this->created_at)->format('Y-m-d'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d'),
        ];
    }
}
