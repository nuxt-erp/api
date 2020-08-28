<?php

namespace App\Models;

class CompanyModules extends ModelService
{

    protected $connection = 'public';

    protected $fillable = [
        'company_id', 'module_id'
    ];

}
