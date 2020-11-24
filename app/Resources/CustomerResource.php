<?php

namespace App\Resources;

class CustomerResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                       => $this->id,
            'country_id'               => $this->country_id,
            'tax_rule_id'              => $this->tax_rule_id,
            'country_name'             => optional($this->country)->name,
            'province_id'              => $this->province_id,
            'province_name'            => optional($this->province)->name,
            'sales_rep_id'             => $this->sales_rep_id,
            'sales_rep_name'           => optional($this->sales_rep)->name,
            'contacts'                 => optional($this->contacts)->toArray(),
            'custom_discounts'         => optional($this->custom_discounts)->toArray(),
            'custom_product_prices'    => optional($this->custom_product_prices)->toArray(),
            'shopify_id'               => $this->shopify_id,
            'name'                     => $this->name,
            'email'                    => $this->email,
            'credit_limit'             => $this->credit_limit,
            'address1'                 => $this->address1,
            'address2'                 => $this->address2,
            'city'                     => $this->city,
            'phone_number'             => $this->phone_number,
            'postal_code'              => $this->postal_code,
            'website'                  => $this->website,
            'note'                     => $this->note,
            'created_at'               => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'tag_ids'                  => optional($this->tags)->pluck('tag_id'),
            'tag_names'                => implode(', ', optional($this->tag_parents)->pluck('tag')->pluck('name')->toArray())

        ];
    }
}
