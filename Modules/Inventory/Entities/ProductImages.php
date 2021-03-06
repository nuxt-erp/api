<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductImages extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_product_images';

    protected $fillable = [
        'product_id', 'path', 'thumb_path',
        'order', 'is_default'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:tenant.inv_products,id'],
            'order'         => ['integer'],
            'is_default'    => ['boolean']
        ];

        // CREATE
        if (is_null($item)){
            $rules['product_id'][] = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}

