<?php

namespace Modules\RD\Entities;
use App\Models\Parameter;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
class Constants {
    const rd_requester        = [
        'pending',
        'waiting approval',
        'waiting qc',
        'ready'

    ];
    const rd_supervisor       = [
        'in progress',
        'sent',
        'approved',
        'rework',
    ];
    const rd_flavorist       = [
        'in progress',
        'waiting approval',
        'approved',
        'rework',
    ];
}
class ProjectSamples extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_project_samples';

    protected $dates = [
        'started_at', 'finished_at',
    ];

    protected $fillable = [
        'project_id', 'recipe_id', 'phase_id', 'assignee_id',
        'internal_code', 'external_code', 'author_id',
        'name', 'status', 'target_cost',
        'feedback', 'comment', 'finished_at',
        'started_at'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'project_id'              => ['exists:tenant.rd_projects,id'],
            'recipe_id'               => ['exists:tenant.rd_recipes,id'],
            'phase_id'                => ['exists:tenant.rd_phases,id'],
            'assignee_id'             => ['exists:users,id'],
            'author_id'               => ['nullable', 'exists:public.users,id'],
            'name'                    => ['nullable', 'max:255'],
            'internal_code'           => ['string', 'max:255'],
            'external_code'           => ['string', 'max:255'],
            'target_cost'             => ['nullable'],
            'feedback'                => ['nullable', 'string', 'max:255'],
            'comment'                 => ['nullable', 'string', 'max:255'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_id'][]      = 'required';
            $rules['status'][]          = 'required';
            $rules['internal_code'][]   = 'required';
            $rules['external_code'][]   = 'required';
        }
        // rules when updating the item
        else{
            $rules['recipe_id'][]       = 'nullable';
            $rules['project_id'][]      = 'nullable';
            $rules['status'][]          = 'nullable';
            $rules['internal_code'][]   = 'nullable';
            $rules['external_code'][]   = 'nullable';
        }

        return $rules;
    }

    static function getStatuses() {
        $oClass = new \ReflectionClass(Constants::class);
        return $oClass->getConstants();
    }

    public function getRecipeVersionQtyAttribute(){
        return 0;
    }

    public function attributes()
    {
        return $this->belongsToMany(Parameter::class, 'rd_project_sample_attributes', 'project_sample_id', 'attribute_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function phase()
    {
        return $this->belongsTo(Phase::class, 'phase_id', 'id');
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
