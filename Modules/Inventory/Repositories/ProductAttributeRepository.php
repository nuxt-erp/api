<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProductAttributeRepository extends RepositoryService
{
    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'attribute_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['value'])) {
            $name = '%' . Arr::pull($searchCriteria, 'value') . '%';
            $this->queryBuilder
            ->where('value', 'LIKE', $name);
        }

        if (!empty($searchCriteria['product_id'])) {
            $this->queryBuilder
            ->where('product_id', Arr::pull($searchCriteria, 'product_id'));
        }

        return parent::getList($searchCriteria);
    }

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['value'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
        }

        if (!empty($searchCriteria['product_id'])) {
            $this->queryBuilder
            ->where('product_id', Arr::pull($searchCriteria, 'product_id'));
        }

        return parent::findBy($searchCriteria);
    }

}
