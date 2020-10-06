<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Phase extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_phases';

    protected $fillable = ['name'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['name'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function flows()
    {
        return $this->hasMany(Flow::class, 'phase_id', 'id');
    }
    public function phase_roles() {
        return $this->hasMany(PhaseRole::class, 'phase_id', 'id');
    }

}
