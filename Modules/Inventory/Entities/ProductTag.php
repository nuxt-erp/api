<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductTag extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_product_tags';

    protected $fillable = [
        'product_id', 'tag_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:tenant.inv_products,id'],
            'tag_id'        => ['exists:tenant.inv_tags,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]      = 'required';
            $rules['tag_id'][]       = 'required';
        }        

        return $rules;
    }
   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
