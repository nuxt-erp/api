<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;

class StockAdjustment extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_stock_adjustments";

    protected $fillable = [
        'notes'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'notes' => ['nullable', 'string'],
        ];
        
        return $rules;
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id');
    }   
}
