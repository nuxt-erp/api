<?php

namespace Modules\RD\Entities;

use App\Models\Customer;
use App\Models\ModelService;
use App\Models\User;
use App\Models\Parameter;
class ProjectConstants {
    const STATUS_PENDING    = 'active';
    const STATUS_AWAITING   = 'awaiting feedback';
    const STATUS_UPDATED    = 'updated';
    const STATUS_FINISHED   = 'finished';
}
class Project extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_projects';



    protected $dates = [
        'start_at', 'closed_at'
    ];

    protected $fillable = [
        'author_id','customer_id', 'status',
        'iteration', 'comment', 'start_at',
        'closed_at'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'author_id'     => ['nullable', 'exists:public.users,id'],
            'customer_id'   => ['exists:tenant.customers,id'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['customer_id'][] = 'required';
            $rules['start_at'][] = 'required';
            $rules['status'][] = 'required';
            $rules['iteration'][] = 'required';
        }
        // rules when updating the item
        else{
        }

        return $rules;
    }
   
    
    static function getStatuses() {
        $oClass = new \ReflectionClass(ProjectConstants::class);
        return $oClass->getConstants();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function attributes() 
    {
        return $this->belongsToMany(Parameter::class, 'rd_project_attributes', 'project_id', 'attribute_id');
    }
    public function samples() {
        return $this->hasMany(ProjectSamples::class, 'project_id', 'id');    
    }
    public function project_logs() 
    {
        return $this->hasMany(ProjectLogs::class, 'project_id', 'id');    
    }
}
