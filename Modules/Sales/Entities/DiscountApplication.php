<?php

namespace Modules\Sales\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class DiscountApplication extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discount_applications';

    protected $fillable = [
        'percent_off', 'amount_off', 'custom_price', 'discount_id'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'discount_id'                   => ['exists:tenant.sal_discounts,id'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['discount_id'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function discount_rules()
    {
        return $this->hasMany(DiscountRule::class, 'discount_application_id', 'id')->with('type_entity');
    }
}
