<?php

namespace Modules\Inventory\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;

class ReceivingRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }
}
