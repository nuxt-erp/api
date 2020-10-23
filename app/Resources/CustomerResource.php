<?php

namespace App\Resources;

class CustomerResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'country_id'    => $this->country_id,
            'tax_rule_id'    => $this->tax_rule_id,
            'country_name'  => optional($this->country)->name,
            'province_id'   => $this->province_id,
            'province_name' => optional($this->province)->name,
            'shopify_id'    => $this->shopify_id,
            'name'          => $this->name,
            'email'         => $this->email,
            'address1'      => $this->address1,
            'address2'      => $this->address2,
            'city'          => $this->city,
            'phone_number'  => $this->phone_number,
            'postal_code'   => $this->postal_code,
            'website'       => $this->website,
            'note'          => $this->note,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),

        ];
    }
}
