<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Config extends ModelService
{

    protected $fillable = [
        'country_id', 'province_id', 'contact_name',
        'email', 'address1', 'address2',
        'city', 'phone_number', 'postal_code',
        'website', 'dear_id', 'dear_key', 'dear_url',
        'shopify_key', 'shopify_password', 'shopify_store_name',
        'shopify_location'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'country_id'        => ['nullable', 'exists:countries,id'],
            'province_id'       => ['nullable', 'exists:provinces,id'],
            'contact_name'      => ['nullable', 'string', 'max:255'],
            'email'             => ['max:255'],
            'address1'          => ['nullable', 'string', 'max:255'],
            'address2'          => ['nullable', 'string', 'max:255'],
            'city'              => ['nullable', 'string', 'max:60'],
            'phone_number'      => ['nullable', 'string', 'max:20'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'website'           => ['nullable', 'string', 'max:255'],

            'dear_id'           => ['nullable', 'string', 'max:255'],
            'dear_key'          => ['nullable', 'string', 'max:255'],
            'dear_url'          => ['nullable', 'string', 'max:255'],

            'shopify_key'       => ['nullable', 'string', 'max:255'],
            'shopify_password'  => ['nullable', 'string', 'max:255'],
            'shopify_store_name'=> ['nullable', 'string', 'max:255'],
            'shopify_location'  => ['nullable', 'string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['email'][] = 'required';
            $rules['email'][] = 'unique:configs';
        }
        else{
            $rules['email'][]    = Rule::unique('configs')->ignore($item->id);
        }

        return $rules;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
