<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use App\Models\Location;

class StockAdjustmentDetail extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_stock_adjustments_details";

    protected $fillable = [
        'stock_adjustment_id', 'product_id', 'location_id',
        'stock_on_hand', 'qty', 'variance',
        'abs_variance', 'notes'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'stock_adjustment_id'       => ['exists:tenant.inv_stock_ajustments,id'],
            'product_id'                => ['exists:tenant.inv_products,id'],
            'location_id'               => ['nullable', 'exists:tenant.locations,id'],
            'stock_on_hand'             => ['integer'],
            'qty'                       => ['integer'],
            'variance'                  => ['integer'],
            'abs_variance'              => ['integer'],
            'notes'                     => ['nullable', 'string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['stock_adjustment_id'][]     = 'required';
            $rules['product_id'][]              = 'required';
            $rules['location_id'][]             = 'required';
            $rules['stock_on_hand'][]           = 'required';
            $rules['qty'][]                     = 'required';
        }

        return $rules;
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
