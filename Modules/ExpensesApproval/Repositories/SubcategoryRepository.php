<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class SubcategoryRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    { 
        return parent::findBy($searchCriteria);
    }
}
