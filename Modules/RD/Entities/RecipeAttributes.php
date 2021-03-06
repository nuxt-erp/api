<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;

use Illuminate\Validation\Rule;

class RecipeAttributes extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_attributes';

    protected $fillable = ['recipe_id', 'attribute_id'];

    public function getRules($request, $item = null)
    {
         // generic rules
         $rules = [
            'recipe_id'               => ['exists:tenant.rd_recipes,id'],
            'attribute_id'            => ['exists:tenant.parameters,id']

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['recipe_id'][] = 'required';
            $rules['attribute_id'][] = 'required';
        }
      

        return $rules;
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }
    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'attribute_id', 'id');
        
    }
}
