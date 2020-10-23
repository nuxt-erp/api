<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Customer extends ModelService
{

    protected $connection = 'tenant';

    protected $fillable = [
        'country_id', 'province_id', 'shopify_id',
        'name', 'email', 'address1',
        'address2', 'city', 'phone_number',
        'postal_code', 'website', 'note',
        'tax_rule_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'country_id'        => ['nullable', 'exists:tenant.countries,id'],
            'province_id'       => ['nullable', 'exists:tenant.provinces,id'],
            'tax_rule_id'       => ['nullable', 'exists:tenant.tax_rules,id'],
            'shopify_id'        => ['nullable', 'string', 'max:255'],
            'name'              => ['nullable', 'string', 'max:255'],
            'email'             => ['nullable', 'max:255'],
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
            // $rules['email'][] = 'required';
            $rules['email'][] = 'unique:tenant.customers';
        }
        else{
            $rules['email'][]    = Rule::unique('tenant.customers')->ignore($item->id);
        }

        return $rules;
    }

    public function tax_rule()
    {
        return $this->belongsTo(TaxRule::class);
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
