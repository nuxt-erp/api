<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use Modules\Inventory\Entities\Product;

class RecipeItems extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_items';

    //@todo drop cost column (we should get from the product)
    protected $fillable = [
        'product_id', 'recipe_id', 'cost',
        'percent', 'quantity'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'             => ['exists:tenant.inv_products,id'],
            'recipe_id'              => ['exists:tenant.rd_recipes,id'],
            'quantity'               => ['nullable'],
            'percent'                => ['nullable']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['product_id'][] = 'required';
            $rules['recipe_id'][] = 'required';
        }
        // rules when updating the item
        else{
        }

        return $rules;

    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
