<?php

namespace Modules\Sales\Entities;

use App\Models\Customer;
use App\Models\ModelService;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Product;

class DiscountRule extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discount_rules';

    protected $fillable = [
        'type', 'type_id', 'discount_id',
        'discount_application_id', 'include', 'exclude',
        'stackable', 'all_products'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'discount_id'                   => ['exists:tenant.sal_discounts,id'],
            'discount_application_id'       => ['exists:tenant.sal_discount_applications,id'],
            'type'                          => ['string', 'max:255'] 
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['discount_id'][] = 'required';
            $rules['discount_application_id'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
    public function discount_application()
    {
        return $this->belongsTo(DiscountApplication::class, 'discount_application_id');
    }
    public function type_entity()
    {
        return $this->morphTo(__FUNCTION__, 'type', 'type_id');
    }
    // public function type_entity()
    // {
    //     $class= null;
    //     switch ($this->type) {
    //         case 'customer':
    //             $class = Customer::class;
    //         break;
    //         case 'product':
    //             $class = Product::class;
    //         break;
    //         case 'brand':
    //             $class = Brand::class;
    //         break;
    //         case 'category':
    //             $class = Category::class;
    //         break;
    //         case 'tag':
    //             $class = Tag::class;
    //         break;
    //     }
    //     return $this->belongsTo($class, 'type_id');
    // }
}
