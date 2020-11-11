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

        if (!empty($searchCriteria['list']))
        {
            $this->queryBuilder->where('is_enabled', true);
        }

        if (!empty($searchCriteria['name']))
        {
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        if (!empty($searchCriteria['text']))
        {
            $this->queryBuilder->where('name', 'ILIKE', '%' . Arr::pull($searchCriteria, 'text') . '%');
        }

        return parent::findBy($searchCriteria);
    }
}
