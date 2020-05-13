<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sale_id', 'product_id', 'qty', 'price', 'discount_value', 'discount_percent', 'total_item', 'shopify_lineitem', 'qty_fulfilled', 'fulfillment_status', 'fulfillment_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:products,id']
        ];
        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

}
