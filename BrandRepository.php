<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class BrandRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {

        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['name']))
        {
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        return parent::findBy($searchCriteria);
    }
}
