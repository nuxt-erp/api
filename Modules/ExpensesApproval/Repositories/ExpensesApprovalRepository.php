<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ExpensesApprovalRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }
}
