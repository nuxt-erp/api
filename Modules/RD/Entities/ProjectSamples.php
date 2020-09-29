<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProjectSamples extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_samples';

    const STATUS_SENT   = 'sent';
    const STATUS_PENDING= 'pending';
    const STATUS_READY  = 'ready';

    protected $fillable = [
        'project_id', 'recipe_id', 'assignee_id',
        'internal_code', 'external_code',
        'name', 'status', 'target_cost',
        'feedback', 'comment'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_id'              => ['exists:tenant.rd_projects,id'],
            'recipe_id'               => ['exists:tenant.rd_recipes,id'],
            'assignee_id'             => ['exists:users,id'],
            'name'                    => ['nullable', 'max:255'],
            'internal_code'           => ['string', 'max:255'],
            'external_code'           => ['string', 'max:255'],
            'status'                  => ['string', 'max:255'],
            'target_cost'             => ['nullable'],
            'feedback'                => ['nullable', 'string', 'max:255'],
            'comment'                 => ['nullable', 'string', 'max:255'],

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][] = 'required';
            $rules['status'][] = 'required';
            $rules['internal_code'][] = 'required';
            $rules['external_code'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }
    public function project_sample_logs() 
    {
        return $this->hasMany(ProjectSampleLogs::class, 'rd_project_sample_logs', 'project_sample_id', 'id');    
    }
}
