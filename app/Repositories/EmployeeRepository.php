<?php

namespace App\Repositories;

use Illuminate\Support\Arr;

class EmployeeRepository extends RepositoryService
{

    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];
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
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        if (!empty($searchCriteria['type'])) {
            $type = Arr::pull($searchCriteria, 'type');
            $this->queryBuilder->whereHas('type', function ($query) use ($type) {
                $query->where('parameters.parameter_value', $type);
            });
        }

        return parent::findBy($searchCriteria);
    }

}
