<?php

namespace Modules\Sales\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class DiscountApplication extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discount_applications';

    protected $fillable = [
        'percent_off', 'amount_off', 'custom_price'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            
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
