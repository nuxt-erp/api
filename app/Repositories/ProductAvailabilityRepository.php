<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProductAvailability;
use Auth;

class ProductAvailabilityRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'products.name',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['product_name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'product_name') . '%';
            $this->queryBuilder
            ->where('products.name', 'LIKE', $name)
            ->orWhere('products.sku', 'LIKE', $name);
        }

        $this->queryBuilder->select('product_availabilities.id', 'product_availabilities.product_id', 'product_availabilities.company_id', 'product_availabilities.available', 'product_availabilities.location_id', 'product_availabilities.on_hand');
        $this->queryBuilder->join('products', 'product_availabilities.product_id', 'products.id');

        if (!empty($searchCriteria['category_id'])) {
            $this->queryBuilder
            ->where('products.category_id', Arr::pull($searchCriteria, 'category_id'));
        }

        if (!empty($searchCriteria['brand_id'])) {
            $this->queryBuilder
            ->where('products.brand_id', Arr::pull($searchCriteria, 'brand_id'));
        }

        $this->queryBuilder->where('product_availabilities.company_id', Auth::user()->company_id);

        return parent::findBy($searchCriteria);
    }


    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id; // COMPANY ID FROM THE CURRENT USER LOGGED
        parent::store($data);
    }

    public function updateStock($product_id, $qty, $location_id, $operator)
    {
        if ($operator == "+") {
            ProductAvailability::where(['company_id' => Auth::user()->company_id, 'product_id' => $product_id, 'location_id' => $location_id])->increment('on_hand', $qty);
        } elseif ($operator == "-") {
            ProductAvailability::where(['company_id' => Auth::user()->company_id, 'product_id' => $product_id, 'location_id' => $location_id])->decrement('on_hand', $qty);
        }
    }

     // USED TO LOAD PRODUCT AVAILABILITIES, STOCK TAKE AND PRODUCTS
     public function productAvailabilities(array $searchCriteria = [])
     {
        $searchCriteria['order_by'] = [
             'field'         => 'name',
             'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 20;

        $this->queryBuilder->select('brands.name as brand_name', 'categories.name as category_name', 'products.id', 'products.name', 'products.sku', 'product_availabilities.location_id', 'product_availabilities.on_hand', 'products.category_id', 'products.brand_id');
        $this->queryBuilder->rightJoin('products', 'product_availabilities.product_id', 'products.id');
        $this->queryBuilder->join('brands', 'brands.id', 'products.brand_id');
        $this->queryBuilder->join('categories', 'categories.id', 'products.category_id');

         // EDITING STOCKTAKE
         if (!empty($searchCriteria['stocktake_id']))
         {
             $this->queryBuilder->addSelect('dt.qty as qty');
             $this->queryBuilder->leftJoin('stocktake_details dt', 'dt.product_id', 'products.id');
         }

         if ($searchCriteria['location_id']) {
            $this->queryBuilder->join('locations', 'locations.id', 'product_availabilities.location_id')->where('product_availabilities.location_id', $searchCriteria['location_id']);
         } else {
            $this->queryBuilder->join('locations', 'locations.id', 'product_availabilities.location_id');
         }

         if (!empty($searchCriteria['location_id'])) {
            $this->queryBuilder
            ->where('product_availabilities.location_id', Arr::pull($searchCriteria, 'location_id'));
         }

         if (!empty($searchCriteria['add_discontinued'])) {
            $this->queryBuilder
            ->where('products.status', Arr::pull($searchCriteria, 'add_discontinued'));
         }

         if (!empty($searchCriteria['stocktake_id'])) {
             $this->queryBuilder
             ->where('stocktake_id', Arr::pull($searchCriteria, 'stocktake_id'));
         }

         if (!empty($searchCriteria['category_id'])) {
             $this->queryBuilder
             ->where('category_id', Arr::pull($searchCriteria, 'category_id'));
         }

         if (!empty($searchCriteria['brand_id'])) {
             $this->queryBuilder
             ->where('brand_id', Arr::pull($searchCriteria, 'brand_id'));
         }

         $this->queryBuilder->where('products.company_id', Auth::user()->company_id);

         return parent::findBy($searchCriteria);

     }
}
