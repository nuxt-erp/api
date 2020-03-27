<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class RoleRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        if (!empty($searchCriteria['name'])) {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        return parent::findBy($searchCriteria);
    }

}
