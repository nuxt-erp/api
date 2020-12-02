<?php

namespace Modules\Sales\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'desc'
        ];

        if (!empty($searchCriteria['order_number']))
        {
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['order_number'] = '%' . Arr::pull($searchCriteria, 'order_number') . '%';
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {

            parent::store($data);
            // Save all products
            $api    = resolve('Shopify\API');

            $api->saveSaleDetails($data['sale_details'], $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // Save all products
            $api    = resolve('Shopify\API');
            $api->saveSaleDetails($data['sale_details'], $this->model->id);
        });
    }
}
