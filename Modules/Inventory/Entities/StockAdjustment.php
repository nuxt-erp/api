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
        'notes', 'author_id', 'name', 'effective_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'author_id' => ['exists:public.users,id'],
            'notes'     => ['nullable', 'string'],
        ];

        return $rules;
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
