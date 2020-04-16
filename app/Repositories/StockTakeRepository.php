<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class StockTakeRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['sku'])) {
            $sku = '%' . Arr::pull($searchCriteria, 'sku') . '%';
            $this->queryBuilder
            ->where('sku', 'LIKE', $sku)
            ->orWhere('name', 'LIKE', $sku);
        }

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', $searchCriteria['id']);
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('category_id', $searchCriteria['category_id']);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('brand_id', $searchCriteria['brand_id']);
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
    }

}
