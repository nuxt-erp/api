<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class CurrencyRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['name']))
        {
            $searchCriteria['query_type']   = 'ILIKE';
            $searchCriteria['name']         = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        if (!empty($searchCriteria['code']))
        {
            $searchCriteria['query_type']   = 'ILIKE';
            $searchCriteria['code']         = '%' . Arr::pull($searchCriteria, 'code') . '%';
        }

        return parent::findBy($searchCriteria);
    }
}
