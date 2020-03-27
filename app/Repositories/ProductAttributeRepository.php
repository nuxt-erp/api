<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductAttributeRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['value']))
        {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['value'] = '%' . Arr::pull($searchCriteria, 'value') . '%';
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        parent::store($data);
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
    }
}
