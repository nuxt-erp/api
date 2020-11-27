<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class StockCountFilter extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_stock_count_filters';

    protected $fillable = [
        'type', 'type_id', 'stocktake_id'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'stocktake_id'                   => ['exists:tenant.inv_stock_counts,id'],
            'type'                          => ['string', 'max:255'] 
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['stocktake_id'][] = 'required';
        }

        return $rules;
    }
    public function stock_count()
    {
        return $this->belongsTo(StockCount::class, 'stocktake_id');
    }
    public function type_entity()
    {
        return $this->morphTo(__FUNCTION__, 'type', 'type_id');
    }
}
