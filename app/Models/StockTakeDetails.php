<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTakeDetails extends Model
{
    public $timestamps  = false;
    public $table       = "stocktake_details";

    protected $fillable = [
        'stocktake_id', 'product_id', 'qty', 'stock_on_hand', 'variance', 'notes', 'location_id', 'abs_variance'
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
