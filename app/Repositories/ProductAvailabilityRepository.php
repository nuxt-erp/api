<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class ProductAvailabilityRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        if(Arr::has($searchCriteria, 'product_name')){
            $this->queryBuilder->whereHas('product', function (Builder $query) use($searchCriteria) {
                $name = '%'.Arr::pull($searchCriteria, 'product_name').'%';
                $query->where('name', 'LIKE', $name)
                ->orWhere('sku', 'LIKE', $name);
            });
        }

        return parent::findBy($searchCriteria);
    }
}
