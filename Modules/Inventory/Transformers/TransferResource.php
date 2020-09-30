<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class TransferResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'pu_date'               => $this->pu_date,            
            'tracking_number'       => $this->tracking_number,
            'carrier_id'            => $this->carrier_id,
            'carrier_name'          => optional($this->parameter_carrier)->value,
            'package_type_id'       => $this->package_type_id,
            'package_type_name'     => optional($this->parameter_package)->value,
            'total_qty'             => $this->total_qty,
            'shipment_type_id'      => $this->shipment_type_id,
            'shipment_type_name'    => optional($this->parameter_shipment)->value,
            'location_from_id'      => $this->location_from_id,
            'location_from_name'    => optional($this->location_from)->name,
            'location_to_id'        => $this->location_to_id,
            'location_to_name'      => optional($this->location_to)->name,
            'eta'                   => $this->eta,
            'is_enable'             => $this->is_enable,
            'status_label'          => ($this->is_enable == true ? "In Progress" : "Received"),
            'can_be_deleted'        => false
        ];
    }
}
