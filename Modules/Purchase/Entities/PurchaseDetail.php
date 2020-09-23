<?php

namespace Modules\Purchase\Entities;

use App\Models\ModelService;
use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Modules\Inventory\Entities\Product;

class PurchaseDetail extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'pur_purchase_details';

    protected $fillable = [
        'purchase_id', 'product_id', 'qty',
        'price', 'sub_total', 'total',
        'estimated_date', 'qty_received', 'received_date',
        'ref', 'item_status', 'taxes',
        'discounts'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'    => ['exists:inv_products,id']
        ];

        // rules when creating the item
        if (is_null($item)) {
            //$rules['field'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
