<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class CustomerDiscountRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

      /*  $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id']))
        {
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['id'] = '%' . Arr::pull($searchCriteria, 'id') . '%';
        }
*/
        return parent::findBy($searchCriteria);
    }

}
