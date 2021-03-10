<?php

namespace Modules\Purchase\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class PurchaseTrackingNumber extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'pur_purchase_tracking_nums';

    protected $fillable = [
        'purchase_id',
        'tracking_number'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'    => ['exists:pur_purchases,id'],

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
    

}
