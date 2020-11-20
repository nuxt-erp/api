<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class RecipeImportSettings extends ModelService
{
    protected $connection = 'tenant';
    protected $table = 'rd_recipe_import_settings';

    protected $fillable = [
        'column_name', 'custom_name', 'entity'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'columns' => ['array']
        ];

        if (is_null($item)) {
            $rules['columns'][]     = 'required';
        }

        return $rules;
    }
}
