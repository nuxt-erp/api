<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Action extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_actions';

    protected $fillable = [
        'code', 'description'
    ];
    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'code'                 => ['string', 'max:255'],
            'description'          => ['string', 'max:255']

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['description'][] = 'required';
            $rules['code'][]    = 'unique:tenant.prod_actions';

        }
        return $rules;
    }
}
