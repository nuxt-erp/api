<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\User;
use App\Models\ModelService;
class Constants {

    const TYPE_ADD       = [ 'add to stock'                 => 0 ];
    const TYPE_REPLACE   = [ 'replace current stock'        => 1 ];
    };
class StockAdjustment extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_stock_adjustments";

    const TYPE_ADD       = 'add to stock';
    const TYPE_REPLACE   = 'replace current stock';

    protected $dates = [
        'effective_date'
    ];

    protected $fillable = [
        'notes', 'author_id', 'location_id', 'adjustment_type', 'name', 'effective_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'notes'            => ['nullable', 'string'],
            'adjustment_type'  => ['nullable', 'string'],
        ];

        return $rules;
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id');
    }
    
    public function getStatusId($status)
    {
        $constants = $this->getStatuses();
        foreach ($constants as $constant_assoc) {
            if (!empty($constant_assoc[$status])) {
                return $constant_assoc[$status];
            }            
        }
        return null;
    }
    public static function getStatuses() {
        $oClass = new \ReflectionClass(Constants::class);
        return $oClass->getConstants();
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
