<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class RecipeSpecificationAttributes extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_specification_attributes';

    protected $fillable = ['recipe_specification_id', 'attribute_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'recipe_specification_id'         => ['exists:tenant.rd_recipe_specification,id'],
            'attribute_id'                    => ['exists:tenant.parameters,id']
        ];
        

        // rules when creating the item
        if (is_null($item)) {
            $rules['recipe_specification_id'][] = 'required';
            $rules['attribute_id'][]            = 'required';
        }
      

        return $rules;
    }

    public function recipe_specification()
    {
        return $this->belongsTo(RecipeSpecification::class, 'recipe_specification_id', 'id');
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'attribute_id', 'id');
        
    }
}
