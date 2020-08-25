<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Customer extends ModelService
{

    protected $fillable = [
        'country_id', 'province_id', 'shopify_id',
        'name', 'email', 'address1',
        'address2', 'city', 'phone_number',
        'postal_code', 'website', 'note'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'country_id'        => ['nullable', 'exists:countries,id'],
            'province_id'       => ['nullable', 'exists:provinces,id'],
            'shopify_id'        => ['nullable', 'string', 'max:255'],
            'name'              => ['nullable', 'string', 'max:255'],
            'email'             => ['max:255'],
            'address1'          => ['nullable', 'string', 'max:255'],
            'address2'          => ['nullable', 'string', 'max:255'],
            'city'              => ['nullable', 'string', 'max:60'],
            'phone_number'      => ['nullable', 'string', 'max:20'],
            'postal_code'       => ['nullable', 'string', 'max:20'],
            'website'           => ['nullable', 'string', 'max:255'],
            'note'              => ['nullable', 'string', 'max:255']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['email'][] = 'required';
            $rules['email'][] = 'unique:customers';
        }
        else{
            $rules['email'][]    = Rule::unique('customers')->ignore($item->id);
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
