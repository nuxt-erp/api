<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Arr;
use Auth;
//use App\Traits\StockTrait;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;

class PurchaseDetailRepository extends RepositoryService
{
    //use StockTrait;

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
            $getItem = DB::table('pur_purchase_details')->where('pur_purchase_details.id', $parseId)
            ->join('pur_purchases', 'pur_purchases.id', 'pur_purchase_details.purchase_id')
            ->first();

            if ($getItem) {

                if ($getItem->status == 1) { // Completed
                    // Decrement stock on hand qty
                    //$this->updateStock(Auth::user()->company_id, $getItem->product_id, $getItem->qty, $getItem->location_id, "-", "Purchase", $id, 0, 0, "Removed item");
                } else {
                    // Decrement stock on order qty
                    //$this->updateStock(Auth::user()->company_id, $getItem->product_id, 0, $getItem->location_id, "-", "Purchase", $id, $getItem->qty, 0, 0, "Removed item");
                }

            }

            parent::delete($id);

        });
    }

}
