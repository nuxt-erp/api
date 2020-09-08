<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProjectItemAttributes extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_item_attributes';

    protected $fillable = ['recipe_id', 'attribute_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'recipe_id'               => ['exists:tenant.rd_recipes,id'],
            'attribute_id'            => ['exists:inv_attributes,id']

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
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
