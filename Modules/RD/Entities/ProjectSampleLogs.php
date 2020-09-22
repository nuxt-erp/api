<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class ProjectSampleLogs extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_sample_logs';

    protected $fillable = ['project_sample_id', 'assignee_id', 'status', 'feedback', 'comment'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_sample_id'   => ['exists:tenant.rd_project_samples,id']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_sample_id'][] = 'required';
            $rules['status'][] = 'required';
        }

        return $rules;
    }
    public function project_sample()
    {
        return $this->belongsTo(ProjectSamples::class, 'project_sample_id');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }
}
