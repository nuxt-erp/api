<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use App\Models\Location;

class ProductReorderLevel extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_product_reorder_levels';
    
    protected $fillable = [
        'product_id', 'location_id', 'safe_stock', 'reorder_qty',    
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'        => ['exists:tenant.inv_products,id'],
            'location_id'       => ['nullable', 'exists:tenant.locations,id']            
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]    = 'required';
            $rules['location_id'][]    = 'required';
        }        

        return $rules;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
