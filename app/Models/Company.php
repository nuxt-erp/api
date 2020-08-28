<?php

namespace App\Models;

class Company extends ModelService
{

    protected $fillable = [
        'owner_id', 'name', 'schema',
        'is_enabled'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'company_modules');
    }
}
