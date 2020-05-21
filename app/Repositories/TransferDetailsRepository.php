<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Auth;
use App\Models\TransferDetails;
use App\Traits\StockTrait;
use Illuminate\Support\Facades\DB;

class TransferDetailsRepository extends RepositoryService
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
            ->where('transfer_id', Arr::pull($searchCriteria, 'id'));
        }

        $searchCriteria['per_page'] = 100;
        return parent::findBy($searchCriteria);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id)
        {
            $parseId = $id["id"];
            $getItem = DB::table('transfer_details')->where('transfer_details.id', $parseId)
            ->join('transfers', 'transfers.id', 'transfer_details.transfer_id')
            ->first();

            if ($getItem) {
                $this->updateStock($getItem->company_id, $getItem->product_id, $getItem->qty, $getItem->location_id, "-", "Transfer", $id);
            }

            parent::delete($id);

        });
    }

}
