<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProjectLogsRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if(!empty($searchCriteria['project_id'])){
            $this->queryBuilder
            ->where('project_id', $searchCriteria['project_id']);
        }
        return parent::findBy($searchCriteria);
    }


}
