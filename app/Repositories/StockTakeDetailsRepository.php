<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class StockTakeDetailsRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }
}
