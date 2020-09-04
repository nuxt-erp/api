<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;
use Illuminate\Validation\Rule;

class Recipe extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipes';

    protected $fillable = ['name', 'code', 'cost', 'status', 'version'];

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

    public function tags()
    {
        return $this->belongsToMany(Parameter::class, 'rd_recipe_tags', 'tag_id', 'id');
    }
}
