<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use  App\Models\SystemParameter;

class SystemParameterRepository extends RepositoryService
{
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

    public function getCountTypeList() {
        return SystemParameter::where('param_name', 'count_type')->orderBy('param_value')->get();
    }
}
