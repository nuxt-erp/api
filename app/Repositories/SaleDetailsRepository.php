<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Traits\StockTrait;
use Illuminate\Support\Facades\DB;

class SaleDetailsRepository extends RepositoryService
{
    use StockTrait;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('sale_id', Arr::pull($searchCriteria, 'id'));
        }

        $searchCriteria['per_page'] = 100;
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        parent::store($data);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id)
        {
            $parseId = $id["id"];
            $getItem = DB::table('sale_details')->where('sale_details.id', $parseId)
            ->join('sales', 'sales.id', 'sale_details.sale_id')
            ->first();

            if ($getItem) {

                if ($getItem->fulfillment_status == 1) {
                    // Decrement on hand qty
                    $this->updateStock($getItem->company_id, $getItem->product_id, $getItem->qty, $getItem->location_id, "+", "Sale", $id);
                } else {
                    // Decrement allocated qty
                    $this->updateStock($getItem->company_id, $getItem->product_id, 0, $getItem->location_id, "+", "Sale", $id, 0, $getItem->qty);
                }
            }

            parent::delete($id);

        });
    }

}
