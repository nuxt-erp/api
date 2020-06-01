<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'company_id'         => $this->company_id,
            'lead_time'          => $this->lead_time,
            'ordering_cycle'     => $this->ordering_cycle,
            'brand_id'           => $this->brand_id,
            'brand_name'         => optional($this->brand)->name,
            'supplier_type_id'   => $this->supplier_type_id,
            'supplier_type_name' => optional($this->supplier_type)->param_value,
            'date_last_order'    => isset($this->date_last_order) ? date('Y-m-d', strtotime($this->date_last_order)) : '',
            'can_be_deleted'     => true
        ];
    }
}
