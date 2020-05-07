<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute;

class PurchaseDetails extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'purchase_id', 'product_id', 'qty', 'price', 'gross_total', 'total', 'estimated_date', 'qty_received', 'received_date', 'ref', 'item_status'
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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

}
