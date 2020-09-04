<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;
use Modules\ExpensesApproval\Entities\ExpensesAttachment;

class ExpensesAttachmentRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }

    public function deleteFile($file_name)
    {   
        ExpensesAttachment::where('file_name', $file_name)->delete();  
    }
}
