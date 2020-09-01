<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ExpensesRuleRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {   
        $searchCriteria['order_by'] = [
            'field'         => 'start_value',
            'direction'     => 'asc'
        ];

        return parent::findBy($searchCriteria);
    }
}
