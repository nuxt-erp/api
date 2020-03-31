<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductRepository extends RepositoryService
{

    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if(!empty($searchCriteria['category_name'])){
            $category = Arr::pull($searchCriteria, 'category_name');
            $this->queryBuilder->whereHas('category', function ($query) use ($category) {
                $query->where('product_categories.name', $category);
            });
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder
            ->where('name', 'LIKE', $name)
            ->orWhere('sku', 'LIKE', $name);
        }

        if (!empty($searchCriteria['sku'])) {
            $this->queryBuilder
            ->where('sku', 'LIKE', '%' . Arr::pull($searchCriteria, 'sku') . '%');
        }

        return parent::getList($searchCriteria);
    }

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

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
            $searchCriteria['sku'] = $name;
        }

        if(Arr::has($searchCriteria, 'complete_name')){
            $searchCriteria['query_type']   = 'LIKE';
            $searchCriteria['where']        = 'OR';
            $name = Arr::pull($searchCriteria, 'complete_name');
            $searchCriteria['sku']          = '%' . $name . '%';
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
    }

}
