<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'pu_date'               => $this->pu_date,
            'company_id'            => $this->company_id,
            'tracking_number'       => $this->tracking_number,
            'carrier_id'            => $this->carrier_id,
            'carrier_name'          => optional($this->carrier)->param_value,
            'package_type_id'       => $this->package_type_id,
            'package_type_name'     => optional($this->package_type)->param_value,
            'total_qty'             => $this->total_qty,
            'shipment_type_id'      => $this->shipment_type_id,
            'shipment_type_name'    => optional($this->shipment_type)->param_value,
            'location_from_id'      => $this->location_from_id,
            'location_from_name'    => optional($this->location_from)->name,
            'location_to_id'        => $this->location_to_id,
            'location_to_name'      => optional($this->location_to)->name,
            'eta'                   => $this->eta,
            'status'                => $this->status,
            'status_label'          => ($this->status == 1 ? "DELIVERED" : "IN-TRANSIT"),
            'can_be_deleted'        => true
        ];
    }
}
