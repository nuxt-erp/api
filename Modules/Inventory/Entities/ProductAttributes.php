<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductAttributes extends ModelService
{
    public $timestamps = false;

    protected $fillable = [
        'value', 'product_id', 'attribute_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'attribute_id'  => ['exists:attributes,id'],
            'product_id'    => ['exists:products,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['value'][]           = 'required';
            $rules['product_id'][]      = 'required';
            $rules['attribute_id'][]    = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}

