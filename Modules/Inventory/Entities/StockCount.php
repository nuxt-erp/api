<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
class Constants {

    const STATUS_DONE          = [ "DONE" => 1 ];
    const STATUS_IN_PROGRESS   = [ "IN PROGRESS" => 0];
    };
class StockCount extends ModelService
{
    protected $connection = 'tenant';

    public $table              = "inv_stock_counts";
 
    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'name', 'date', 'brand_id',
        'category_id', 'location_id',
        'count_type_id', 'skip_today_received', 'add_discontinued',
        'variance_last_count_id', 'status'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:45'],
        ];

        // CREATE
        if (is_null($item)) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }
    static function getStatuses()
    {
        $oClass = new \ReflectionClass(Constants::class);
        return $oClass->getConstants();
    }
    public function details()
    {
        return $this->hasManySync(StockCountDetail::class, 'stockcount_id', 'id');
    }
    public function stock_filters()
    {
        return $this->hasMany(StockCountFilter::class, 'stocktake_id', 'id')->with('type_entity');
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
        return $this->belongsTo(Category::class, 'category_id');
    }
}
