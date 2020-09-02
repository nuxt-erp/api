<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Project extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_projects';

    protected $fillable = ['customer_id', 'user_id', 'is_enable', 'comments', 'closed_at'];

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
