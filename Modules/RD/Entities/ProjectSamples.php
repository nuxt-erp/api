<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProjectSamples extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_samples';

    protected $fillable = ['project_id', 'recipe_id'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_id'              => ['exists:tenant.rd_projects,id'],
            'recipe_id'               => ['exists:tenant.rd_recipes,id'],
            'assignee_id'             => ['exists:users,id'],
            'name'                    => ['nullable', 'max:255'],
            'status'                  => ['max:255'],
            'target_cost'             => ['nullable', 'float'],
            'feedback'                => ['nullable', 'max:255'],
            'comment'                 => ['nullable', 'max:255'],

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][] = 'required';
            $rules['recipe_id'][] = 'required';
            $rules['assignee_id'][] = 'required';
            $rules['status'][] = 'required';
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
}
