<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;

class StockTake extends Model
{
    public $table       = "inv_stocktakes";

    protected $fillable = [
        'name', 'date', 'brand_id', 'category_id', 'location_id', 'target', 'count_type_id', 'skip_today_received', 'add_discontinued', 'variance_last_count_id', 'company_id', 'status'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:45'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
