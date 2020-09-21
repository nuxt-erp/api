<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProjectLogs extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_logs';

    protected $fillable = [
        'project_id','status', 'code',
        'comment'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_id'   => ['exists:tenant.rd_projects,id']
        ];


        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][] = 'required';
            $rules['status'][] = 'required';
            $rules['code'][] = 'required';
            $rules['comment'][] = 'required';
        }

        return $rules;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
