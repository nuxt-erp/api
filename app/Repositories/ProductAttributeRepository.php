<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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

    public function store($data)
    {
        parent::store($data);
    }

    public function update($model, array $data)
    {
        parent::update($model, $data);
    }

    // GENERATE FAMILY PRODUCT
    /*
    attribute_id product_id value
    1               1        11
    2               1        21
    3               1        31
    4               1        41
    */
    public function generate($data)
    {
        // LOOP INTO $data, check same attribute_id, if found create combinations with all other attributes
        $sorted = Arr::sort($data);

        foreach ($sorted as $key => $value) {
            if ($key === "attribute_id") {

            }
        }
    }
}
