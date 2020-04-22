<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProductAvailabilityRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'products.name',
            'direction'     => 'asc'
        ];

       //

        $searchCriteria['per_page'] = 100;

        $this->queryBuilder->select('product_availabilities.id', 'product_availabilities.product_id', 'product_availabilities.company_id', 'product_availabilities.available', 'product_availabilities.location_id', 'product_availabilities.on_hand');
        $this->queryBuilder->join('products', 'product_availabilities.product_id', 'products.id');

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('products.category_id', $searchCriteria['category_id']);
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('products.brand_id', $searchCriteria['brand_id']);
        }

        $this->queryBuilder->where('product_availabilities.company_id', Auth::user()->company_id);

        return parent::findBy($searchCriteria);
    }
}
