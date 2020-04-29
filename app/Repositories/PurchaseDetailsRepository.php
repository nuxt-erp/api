<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;

class PurchaseDetailsRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 100;

        $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        $data["company_id"] = Auth::user()->company_id;
        parent::store($data);
    }

}
