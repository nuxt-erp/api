<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class LocationRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['name'])) {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }
}
