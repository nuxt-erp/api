<?php

namespace Modules\Inventory\Entities;

use App\Models\Currency;
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
        'product_id', 'customer_id', 'currency_id',
        'custom_price','is_enabled', 'disabled_at',
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'        => ['exists:tenant.inv_products,id'],
            'customer_id'       => ['exists:tenant.customers,id'],
            'currency_id'       => ['exists:tenant.currencies,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]    = 'required';
            $rules['customer_id'][]   = 'required';
            $rules['custom_price'][]  = 'required';
            $rules['currency_id'][]   = 'required';
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

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
