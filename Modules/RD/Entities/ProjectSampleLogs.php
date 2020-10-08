<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class ProjectSampleLogs extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_sample_logs';

    protected $fillable = ['project_sample_id', 'project_id', 'recipe_id', 
                            'updater_id', 'assignee_id','name', 
                            'internal_code', 'external_code', 'status',
                            'feedback', 'comment', 'is_start'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'updater_id'              => ['nullable', 'exists:public.users,id'],
            'project_sample_id'       => ['exists:tenant.rd_project_samples,id'],
            'project_id'              => ['exists:tenant.rd_projects,id'],
            'recipe_id'               => ['nullable', 'exists:tenant.rd_recipes,id'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            
            $rules['project_sample_id'][] = 'required';
            $rules['project_id'][] = 'required';
            $rules['updater_id'][] = 'required';
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
    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id', 'id');
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
