<?php

namespace Modules\Purchase\Repositories;

use Illuminate\Support\Arr;
use Auth;
//use App\Traits\StockTrait;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Inventory\Entities\Availability;

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

    public function delete($item)
    {
        DB::transaction(function () use ($item)
        {
            $detail = PurchaseDetail::find($item->id);
            $availability_repository = new AvailabilityRepository(new Availability());

            if ($detail) {

                if ($detail->status == 1) { // Completed
                    // Decrement stock on hand qty
                    $availability_repository->updateStock($detail->product_id, $detail->qty_received, $detail->location_id, null, "-", "Purchase", $item->id, 0, 0, "Remove item");
                } else {
                    // Decrement stock on order qty
                    $availability_repository->updateStock($detail->product_id, 0, $detail->location_id, null, "-", "Purchase", $item->id, $detail->qty, 0, "Remove item");
                }

            }
            parent::delete($item);

        });
    }

}
