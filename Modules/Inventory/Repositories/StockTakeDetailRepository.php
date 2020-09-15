<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Support\Arr;

class StockTakeDetailRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('stocktake_id', $searchCriteria['id']);
        }

        return parent::findBy($searchCriteria);
    }
}
