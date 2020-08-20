<?php

namespace App\Models;

use Modules\Inventory\Entities\Brand;
use Illuminate\Validation\Rule;

class Supplier extends ModelService
{
    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'supplier_type_id', 'brand_id', 'name',
        'lead_time', 'ordering_cycle',  'last_order_at',
        'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'supplier_type_id'  => ['nullable', 'exists:parameters,id'],
            'brand_id'          => ['nullable', 'exists:inv_brands,id'],
            'name'              => ['string', 'max:60'],
            'lead_time'         => ['nullable', 'integer'],
            'ordering_cycle'    => ['nullable', 'integer'],
            'is_enabled'        => ['nullable', 'boolean'],
            'disabled_at'       => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
            $rules['name'][] = 'unique:suppliers';
        }
        else{
            $rules['name'][]    = Rule::unique('suppliers')->ignore($item->id);
        }

        return $rules;
    }

    public function supplier_type()
    {
        return $this->belongsTo(Parameter::class, 'supplier_type_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
