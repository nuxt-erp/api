<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class FamilyAttributeRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by'] = [
                'field'         => 'attribute_id',
                'direction'     => 'asc'
            ];
        }


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