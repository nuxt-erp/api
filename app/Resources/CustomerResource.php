<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'date_last_order'    => $this->date_last_order,
            'can_be_deleted'     => true
        ];
    }
}
