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

        return parent::findBy($searchCriteria);
    }
}
