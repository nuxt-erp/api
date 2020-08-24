<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use \App\Models\ProductLog;

class ProductLogRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['product_id'])) {
            $this->queryBuilder
            ->where('product_id', Arr::pull($searchCriteria, 'product_id'));
        }

        return parent::findBy($searchCriteria);
    }

    public function getLog(array $searchCriteria = [])
    {
        $data = [];

        $searchCriteria['order_by'] = [
            'field'         => 'date',
            'direction'     => 'desc'
        ];

        $searchCriteria['per_page'] = 10;

        if (!empty($searchCriteria['product_id']))
        {
            // $data = ProductLog::where('product_id', $searchCriteria['product_id'])->get();
            return parent::findBy($searchCriteria);
        }

        return $data;
    }

}
