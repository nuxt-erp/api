<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProjectSamples extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_samples';

    protected $fillable = ['project_id', 'recipe_id'];

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
}
