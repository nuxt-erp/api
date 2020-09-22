<?php

namespace Modules\RD\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProjectSamplesRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }
}
