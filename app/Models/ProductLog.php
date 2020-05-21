<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    public $timestamps = false;
    const TYPE_LOG_SALE = 'Sale';
    const TYPE_LOG_PURCHASE = 'Purchase';
    const TYPE_LOG_TRANSFER = 'Transfer';
    const TYPE_LOG_ADJUSTMENT = 'Adjustment';
    const TYPE_LOG_STOCK_COUNT = 'Stock Count';

    protected $fillable = [
        'product_id', 'location_id', 'date', 'quantity', 'ref_code_id', 'type'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:products,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][]      = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function source()
    {
        if ($this->type == self::TYPE_LOG_SALE) {
            $get = Sale::where('id', $this->ref_code_id)->with('customer')->first();
            if ($get) {
                return $get->customer->name;
            }
        }
    }

}

