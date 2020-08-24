<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductFamilyAttributeRepository extends RepositoryService
{
    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'attribute_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['value'])) {
            $value = '%' . Arr::pull($searchCriteria, 'value') . '%';
            $this->queryBuilder
            ->where('value', 'LIKE', $value);
        }

        return parent::getList($searchCriteria);
    }

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['value'])) {
            $value = '%' . Arr::pull($searchCriteria, 'value') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['value'] = $value;
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
