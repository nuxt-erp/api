<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Role;
use Illuminate\Validation\Rule;

class PhaseRole extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_phase_roles';

    protected $fillable = ['role_id', 'phase_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'phase_id'    => ['exists:tenant.rd_phases,id'],
            'role_id'     => ['exists:tenant.roles,id'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['phase_id'][] = 'required';
            $rules['role_id'][]  = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function phase()
    {
        return $this->belongsTo(Phase::class, 'phase_id', 'id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
