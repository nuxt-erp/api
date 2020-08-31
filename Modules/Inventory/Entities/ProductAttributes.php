<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductAttributes extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_product_attributes';

    protected $fillable = [
        'value', 'product_id', 'attribute_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'attribute_id'  => ['exists:inv_attributes,id'],
            'product_id'    => ['exists:inv_products,id'],
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

