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
    {   $a=1;
        lad($a);
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

        if (!empty($searchCriteria['category_name'])) {
            $category = Arr::pull($searchCriteria, 'category_name');
            $this->queryBuilder->whereHas('category', function ($query) use ($category) {
                $query->where('product_categories.name', $category);
            });
        }

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
                ->where('category_id', $searchCriteria['category_id']);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
                ->where('brand_id', $searchCriteria['brand_id']);
        }
        if (!empty($searchCriteria['stock_locator'])) {
            $this->queryBuilder
                ->where('stock_locator', $searchCriteria['stock_locator']);
        }
        if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
                ->where('location_id', $searchCriteria['location_id']);
        }
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