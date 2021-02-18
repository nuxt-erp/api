<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\User;
use App\Models\ModelService;

class StockAdjustment extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_stock_adjustments";

    protected $dates = [
        'effective_date'
    ];

    protected $fillable = [
        'notes', 'author_id', 'location_id', 'name', 'effective_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'notes'         => ['nullable', 'string'],
        ];

        return $rules;
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id');
    }

    public function detailsWithLocationNames()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id')->with('location');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
