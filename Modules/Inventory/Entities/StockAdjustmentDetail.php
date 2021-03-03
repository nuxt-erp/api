<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use App\Models\Location;
use Modules\Inventory\Entities\LocationBin;

class StockAdjustmentDetail extends ModelService
{
    const ADJUSTED   = 'adjusted';

    const TYPE_ADD       = 'add';
    const TYPE_REPLACE   = 'replace';

    protected $connection = 'tenant';

    public $table         = "inv_stock_adjustments_details";

    protected $fillable   = [
        'stock_adjustment_id', 'product_id', 'location_id',
        'stock_on_hand', 'qty', 'variance', 'adjustment_type',
        'abs_variance', 'notes', 'bin_id', 'status'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'stock_adjustment_id'       => ['exists:tenant.inv_stock_adjustments,id'],
            'product_id'                => ['exists:tenant.inv_products,id'],
            'location_id'               => ['nullable', 'exists:tenant.locations,id'],
            'bin_id'                    => ['nullable', 'exists:tenant.inv_location_bins,id'],
            'stock_on_hand'             => ['integer'],
            'qty'                       => ['integer'],
            'variance'                  => ['integer'],
            'abs_variance'              => ['integer'],
            'notes'                     => ['nullable', 'string', 'max:255'],
            'adjustment_type'           => ['nullable', 'string', 'max:255'],
            'status'                    => ['string'],
        ];

        // CREATE
        if (is_null($item)) {
            $rules['stock_adjustment_id'][]     = 'required';
            $rules['product_id'][]              = 'required';
            $rules['location_id'][]             = 'required';
            $rules['stock_on_hand'][]           = 'required';
            $rules['qty'][]                     = 'required';
            $rules['status'][]                  = 'required';
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

    public function bin()
    {
        return $this->belongsTo(LocationBin::class, 'bin_id');
    }
}
