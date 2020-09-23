<?php

namespace App\Models;

class Company extends ModelService
{
    protected $connection = 'public';

    protected $fillable = [
        'name', 'schema', 'owner_id'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'company_modules');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
