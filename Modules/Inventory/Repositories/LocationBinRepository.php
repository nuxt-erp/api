<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class LocationBinRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {

        if(!empty($searchCriteria['name']))
        {
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['name'] = '%' . Arr::pull($searchCriteria, 'name') . '%';
        }

        return parent::findBy($searchCriteria);
    }
}
