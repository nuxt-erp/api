<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;

class ReceivingDetailRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }
}
