<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;

class ExpensesAttachmentRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    } 
}
