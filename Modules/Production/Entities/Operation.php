<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Operation extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_operations';

    protected $fillable = ['name'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'name'                       => ['string', 'max:255'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['name'][] = 'required';
            $rules['name'][]    = 'unique:tenant.prod_machines';
        }
        

        return $rules;
    }
}
