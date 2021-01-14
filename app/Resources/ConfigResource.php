<?php

namespace App\Resources;

class ConfigResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                                    => $this->id,
            'country_id'                            => $this->country_id,
            'country_name'                          => optional($this->country)->name,
            'province_id'                           => $this->province_id,
            'province_name'                         => optional($this->province)->name,
            'contact_name'                          => $this->contact_name,
            'email'                                 => $this->email,
            'address1'                              => $this->address1,
            'address2'                              => $this->address2,
            'city'                                  => $this->city,
            'phone_number'                          => $this->phone_number,
            'postal_code'                           => $this->postal_code,
            'website'                               => $this->website,
            'dear_id'                               => $this->dear_id,
            'dear_key'                              => $this->dear_key,
            'dear_url'                              => $this->dear_url,
            'shopify_key'                           => $this->shopify_key,
            'shopify_password'                      => $this->shopify_password,
            'shopify_store_name'                    => $this->shopify_store_name,
            'shopify_location'                      => $this->shopify_location,
            'shopify_sync_sales'                    => $this->shopify_sync_sales,
            'dear_automatic_sync'                   => $this->dear_automatic_sync,
            'dear_sync_existing_brands'             => $this->dear_sync_existing_brands,
            'dear_sync_existing_categories'         => $this->dear_sync_existing_categories,
            'dear_sync_existing_products'           => $this->dear_sync_existing_products,
            'dear_sync_existing_product_sizes'      => $this->dear_sync_existing_product_sizes,
            'dear_sync_existing_product_strengths'  => $this->dear_sync_existing_product_strengths,
            'dear_sync_existing_availabilities'     => $this->dear_sync_existing_availabilities
        ];
    }
}
