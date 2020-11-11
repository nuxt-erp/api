<?php

namespace Modules\Sales\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Discount extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discounts';

    protected $fillable = [
        'title', 'order_rule_operation', 'order_rule_value',
        'start_date', 'end_date'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'title'                          => ['string', 'max:255'],
            'order_rule_value'               => ['string', 'max:255']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['title'][] = 'required';
        }

        return $rules;
    }
}
