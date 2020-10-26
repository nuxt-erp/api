<?php

namespace App\Models;

use Modules\Inventory\Entities\Brand;
use Illuminate\Validation\Rule;

class Supplier extends ModelService
{
    protected $connection = 'tenant';

    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'supplier_type_id', 'name', 'brand_id',
        'lead_time', 'ordering_cycle',  'last_order_at',
        'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'supplier_type_id'  => ['nullable', 'exists:tenant.parameters,id'],
            'brand_id'          => ['nullable', 'exists:tenant.inv_brands,id'],
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
            $rules['name'][] = 'unique:tenant.suppliers';
        }
        else{
            $rules['name'][]    = Rule::unique('tenant.suppliers')->ignore($item->id);
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
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'entity_id', 'id')->where('entity_type' , 'supplier');
    }
}
