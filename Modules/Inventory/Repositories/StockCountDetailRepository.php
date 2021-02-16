<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;

class StockCountDetailRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('stockcount_id', $searchCriteria['id']);
        }
        $this->queryBuilder->with(['product', 'product.brand', 'product.category', 'bin', 'location']);

        return parent::findBy($searchCriteria);
    }
}
