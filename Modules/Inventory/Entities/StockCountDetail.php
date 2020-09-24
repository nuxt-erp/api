<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use App\Models\Location;

class StockCountDetail extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_stock_count_details";

    protected $fillable = [
        'stockcount_id', 'product_id', 'qty',
        'stock_on_hand', 'variance', 'notes',
        'location_id', 'abs_variance'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
