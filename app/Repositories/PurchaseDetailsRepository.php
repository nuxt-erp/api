<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\PurchaseDetail;
use App\Traits\StockTrait;
use Illuminate\Support\Facades\DB;

class PurchaseDetailsRepository extends RepositoryService
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
            ->where('purchase_id', Arr::pull($searchCriteria, 'id'));
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
            $getItem = DB::table('purchase_details')->where('purchase_details.id', $parseId)
            ->join('purchases', 'purchases.id', 'purchase_details.purchase_id')
            ->first();

            if ($getItem) {
                // DECREMENT STOCK
                $this->updateStock($getItem->product_id, $getItem->qty, $getItem->location_id, "-");
            }

            parent::delete($id);

        });
    }

}
