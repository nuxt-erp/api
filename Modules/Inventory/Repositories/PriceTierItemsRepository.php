<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\PriceTierItems;
use Modules\Inventory\Entities\Product;

class PriceTierItemsRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by'] = [
                'field'         => 'id',
                'direction'     => 'desc'
            ];
        }
        lad($searchCriteria);
        if (!empty($searchCriteria['product_id']))
        {
            $product_id = Arr::pull($searchCriteria, 'product_id');
            $this->queryBuilder->where('product_id', $product_id);
        }

        return parent::findBy($searchCriteria);
    }


   
}
