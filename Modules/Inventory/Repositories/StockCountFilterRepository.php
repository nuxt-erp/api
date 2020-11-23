<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;

class StockCountFilterRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            parent::store($data);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            parent::update($model, $data);
        });
    }
}
