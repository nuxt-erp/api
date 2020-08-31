<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductFamilyAttribute extends ModelService
{

    protected $connection = 'tenant';

    protected $fillable = [
        'value', 'family_id', 'attribute_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'attribute_id'  => ['exists:attributes,id']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['value'][]           = 'required';
            $rules['family_id'][]      = 'required';
            $rules['attribute_id'][]    = 'required';
        }

        return $rules;
    }

    public function family()
    {
        return $this->belongsTo(ProductFamily::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}

