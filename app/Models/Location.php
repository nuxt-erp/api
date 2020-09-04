<?php

namespace App\Models;

class Location extends ModelService
{

    protected $connection = 'tenant';

    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'dear_id', 'shopify_id', 'name',
        'short_name', 'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'dear_id'       => ['nullable', 'max:255'],
            'shopify_id'    => ['nullable', 'max:255'],
            'name'          => ['string', 'max:255'],
            'short_name'    => ['nullable', 'string', 'max:255'],
            'is_enabled'    => ['nullable', 'boolean'],
            'disabled_at'   => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['dear_id'][]     = 'unique:tenant.locations';
            $rules['shopify_id'][]  = 'unique:tenant.locations';
            $rules['name'][]        = 'unique:tenant.locations';
            $rules['name'][]        = 'required';
        } else {
            //update
            $rules['dear_id'][]     = Rule::unique('tenant.locations')->ignore($item->id);
            $rules['shopify_id'][]  = Rule::unique('tenant.locations')->ignore($item->id);
            $rules['name'][]        = Rule::unique('tenant.locations')->ignore($item->id);
        }

        return $rules;
    }
}
