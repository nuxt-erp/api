<?php

namespace App\Models;
class Role extends ModelService
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    public function operations(){
        return $this->belongsToMany(Operation::class, 'operations_roles')->withTimestamps();
    }
}
