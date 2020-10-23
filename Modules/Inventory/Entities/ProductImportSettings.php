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
        'column_name', 'custom_name'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'column_name'   => ['string', 'max:255'],
            'custom_name'   => ['string', 'max:255']
        ];

        if (is_null($item)) {
            $rules['column_name'][]    = 'required';
            $rules['custom_name'][]    = 'required';
        }

        return $rules;
    }
}
