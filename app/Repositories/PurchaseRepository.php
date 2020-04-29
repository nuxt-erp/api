<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class PurchaseRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'ref_code',
            'direction'     => 'asc'
        ];

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
    }

}
