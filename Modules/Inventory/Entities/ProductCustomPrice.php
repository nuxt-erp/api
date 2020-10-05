<?php

namespace Modules\Inventory\Entities;

use App\Models\Customer;
use App\Models\ModelService;

class ProductCustomPrice extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_prod_custom_prices';

    protected $dates = [
        'disabled_at',
    ];
    
    protected $fillable = [
        'product_id', 'customer_id', 'currency',
        'custom_price','is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'        => ['exists:tenant.inv_products,id'],
            'customer_id'       => ['exists:tenant.customers,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]    = 'required';
            $rules['customer_id'][]   = 'required';
            $rules['currency'][]      = 'required';
            $rules['custom_price'][]  = 'required';
        }        

        return $rules;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
