<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class ProductAvailability extends ModelService
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'warehouse_id', 'location_id',
        'available_quantity'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'product_id'            => ['exists:products,id'],
            'warehouse_id'          => ['exists:warehouses,id'],
            'location_id'           => ['nullable', 'exists:locations,id'],
            'available_quantity'    => ['integer']

        ];
        //create
        if (is_null($item)) {
            $rules['product_id'][]          = 'required';
            $rules['warehouse_id'][]        = 'required';
            $rules['available_quantity'][]  = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
