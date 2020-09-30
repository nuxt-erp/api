<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;

use Illuminate\Validation\Rule;

class ProjectSampleAttributes extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_sample_attributes';

    protected $fillable = ['project_sample_id', 'attribute_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_sample_id'               => ['exists:tenant.rd_project_samples,id'],
            'attribute_id'            => ['exists:tenant.parameters,id']

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_sample_id'][] = 'required';
            $rules['attribute_id'][] = 'required';
        }
      

        return $rules;
    }
    public function project_sample()
    {
        return $this->belongsTo(ProjectSamples::class, 'project_sample_id', 'id');
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'attribute_id', 'id');
        
    }
}
