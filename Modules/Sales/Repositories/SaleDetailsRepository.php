<?php

namespace Modules\Sales\Repositories;

use App\Repositories\RepositoryService;

use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Sales\Entities\SaleDetails;

class SaleDetailsRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        $searchCriteria['per_page'] = 100;
        return parent::findBy($searchCriteria);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id)
        {
            $sale_detail = SaleDetails::find($id);
            //@todo check if inventory module is enabled
            $availability_repository = new AvailabilityRepository(new Availability());

            if ($sale_detail) {

                if ($sale_detail->fulfillment_status->name == 'success') {
                    // Decrement on hand qty
                    $availability_repository->updateStock($sale_detail->product_id, $sale_detail->qty, $sale_detail->location_id, null, "+", "Sale", $id, 0, 0, "Remove item");
                } else {
                    // Decrement allocated qty
                    $availability_repository->updateStock($sale_detail->product_id, 0, $sale_detail->location_id, null, "+", "Sale", $id, 0, $sale_detail->qty, "Remove Item");
                }
            }

            parent::delete($id);

        });
    }

}
