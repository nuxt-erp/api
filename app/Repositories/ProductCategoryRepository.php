<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class ProductCategoryRepository extends RepositoryService
{

    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['name'])) {
            $this->queryBuilder
            ->where('name', 'LIKE', '%' . Arr::pull($searchCriteria, 'name') . '%');
        }

        return parent::getList($searchCriteria);
    }

    public function findBy(array $searchCriteria = [])
    {

        if (!empty($searchCriteria['name'])) {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        return parent::findBy($searchCriteria);
    }

}
