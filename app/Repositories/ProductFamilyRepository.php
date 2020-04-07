<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductFamilyRepository extends RepositoryService
{
    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'family_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder
            ->where('name', 'LIKE', $name);
        }

        return parent::getList($searchCriteria);
    }

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

    public function store($data)
    {
        parent::store($data);
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
    }

}
