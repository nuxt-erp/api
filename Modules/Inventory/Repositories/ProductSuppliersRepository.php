<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProductSuppliersRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }

}
