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
            'email'              => $this->email,
            'address1'           => $this->address1,
            'address2'           => $this->address2,
            'city'               => $this->city,
            'country_id'         => $this->country_id,
            'country_name'       => optional($this->country)->name,
            'province_id'        => $this->province_id,
            'province_name'      => optional($this->province)->name,
            'postal_code'        => $this->postal_code,
            'notes'              => $this->notes,
            'phone_number'       => $this->phone_number,
            'can_be_deleted'     => true
        ];
    }
}
