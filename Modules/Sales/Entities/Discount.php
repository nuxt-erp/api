<?php

namespace Modules\Sales\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use PHPShopify\DiscountCode;

class Discount extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discounts';

    protected $fillable = [
        'title', 'order_rule_operation', 'order_rule_value',
        'start_date', 'end_date', 'stackable'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'title'                          => ['string', 'max:255']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['title'][] = 'required';
        }

        return $rules;
    }
    public function discount_rules()
    {
        return $this->hasMany(DiscountRule::class, 'discount_id', 'id')->with(['discount_application', 'type_entity']);
    }
    public function customer_tags()
    {
        return $this->hasMany(DiscountTag::class, 'discount_id', 'id')->with('tag')->where('type' , 'customer');
    }
    public function all_tags()
    {
        return $this->hasMany(DiscountTag::class, 'discount_id', 'id')->with('tag');
    }
}
