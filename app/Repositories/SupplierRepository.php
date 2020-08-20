<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class SupplierRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
        }

        return parent::findBy($searchCriteria);
    }

}
