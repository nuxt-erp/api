<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;

use Illuminate\Validation\Rule;

class ProjectAttributes extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_attributes';

    protected $fillable = ['project_id', 'attribute_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_id'               => ['exists:tenant.rd_projects,id'],
            'attribute_id'            => ['exists:tenant.parameters,id']

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][] = 'required';
            $rules['attribute_id'][] = 'required';
        }
      

        return $rules;
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'attribute_id', 'id');
        
    }
}
