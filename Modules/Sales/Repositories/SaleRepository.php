<?php

namespace Modules\Sales\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class SaleRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by'] = [
                'field'         => 'order_number',
                'direction'     => 'desc'
            ];
        }

        if (!empty($searchCriteria['order_number']))
        {
            $searchCriteria['query_type'] = 'LIKE';
            $searchCriteria['order_number'] = '%' . Arr::pull($searchCriteria, 'order_number') . '%';
        }

        return parent::findBy($searchCriteria);
    }
}
