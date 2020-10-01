<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductPromo extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_product_promos';

    protected $dates = [
        'date_from', 'date_to'
    ];
    
    protected $fillable = [
        'product_id', 'discount_percentage', 'buy_qty', 
        'get_qty', 'date_from', 'date_to', 'gift_product_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'        => ['exists:tenant.inv_products,id'],
            'gift_product_id'   => ['nullable','exists:tenant.inv_products,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]      = 'required';
            $rules['date_from'][]       = 'required';
            $rules['date_to'][]         = 'required';

        }        

        return $rules;
    }
   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function gift_product()
    {
        return $this->belongsTo(Product::class, 'gift_product_id');
    }
}
