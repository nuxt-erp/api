<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProductImportSettings extends ModelService
{

    protected $connection = 'tenant';
    protected $table = 'inv_products_import_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'column_name', 'custom_name', 'entity'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'columns' => ['array'],
        ];

        if (is_null($item)) {
            $rules['columns'][]     = 'required';
        }

        return $rules;
    }
}
