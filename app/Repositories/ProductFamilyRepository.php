<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductFamily;

class ProductFamilyRepository extends RepositoryService
{
    public function getList(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'family_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder
            ->where('name', 'LIKE', $name);
        }

        return parent::getList($searchCriteria);
    }

    public function getListProducts(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'attribute_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        if (!empty($searchCriteria['family_id']))
        {
            $data = Product::where('family_id', $searchCriteria['family_id'])->get();
        }

        return $data;
    }


    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
        }

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
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

    public function remove($id)
    {
        Product::where('family_id', $id)->delete();
        // parent::delete($id);
        ProductFamily::where('id', $id)->delete();
    }
}
