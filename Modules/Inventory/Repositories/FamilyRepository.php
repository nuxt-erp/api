<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Product;

class FamilyRepository extends RepositoryService
{

    public function getListProducts(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'attribute_id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 50;

        $data = [];
        if (!empty($searchCriteria['family_id'])) {
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

        return parent::findBy($searchCriteria);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            Product::where('family_id', $id)->delete();
            parent::delete($id);
        });
    }
}
