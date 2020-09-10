<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class RecipeItems extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_items';

    protected $fillable = ['product_id', 'recipe_id', 'cost', 'percent', 'quantity' ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'             => ['exists:tenant.inv_products,id'],
            'recipe_id'              => ['exists:tenant.rd_recipes,id'],
            'quantity'               => ['nullable'],
            'percent'                => ['nullable'],
            'cost'                   => ['nullable']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['product_id'][] = 'required';
            $rules['recipe_id'][] = 'required';
            $rules['percent'][]     =
                function ($attribute, $value, $fail) use ($request) {
                    $sum = self::where('recipe_id', $request->input('recipe_id'))->sum('percent');
                    if ((($sum * 100) + $value) > 100) {
                        $fail('more100%');
                    }
                };
            
        }
        // rules when updating the item
        else{
            $rules['percent'][]     =
            function ($attribute, $value, $fail) use ($item) {
                $sum = self::where('id', '<>', $item->id)->where('recipe_id', $item->recipe_id)->sum('percent');
                if ((($sum * 100) + $value) > 100) {
                    $fail('more100%');
                }
            };
        }

        return $rules;

    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
