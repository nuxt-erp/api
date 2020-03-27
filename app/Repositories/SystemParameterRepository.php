<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class SystemParameterRepository extends RepositoryService
{

    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'param_name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['param_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'param_name') . '%';
            $this->queryBuilder
            ->where('param_name', 'LIKE', $name)
            ->where('company_id', Auth::user()->company_id);
        } else {
            $this->queryBuilder
            ->where('company_id', Auth::user()->company_id);
        }

        return parent::getList($searchCriteria);
    }

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['param_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'param_name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['param_name'] = $name;
        }
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
    }
}
