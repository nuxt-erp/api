<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class StockTakeDetailsRepository extends RepositoryService
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
