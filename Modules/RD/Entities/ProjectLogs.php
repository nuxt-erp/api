<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class ProjectLogs extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_logs';

    protected $fillable = [
        'project_id', 'updater_id', 'status', 'code',
        'comment'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'updater_id'   => ['nullable', 'exists:public.users,id'],
            'project_id'   => ['exists:tenant.rd_projects,id']
        ];


        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][] = 'required';
            $rules['updater_id'][] = 'required';
        }

        return $rules;
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id');
    }
}
