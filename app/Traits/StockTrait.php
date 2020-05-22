<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;

trait StockTrait
{
    public function updateStock($company_id, $product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty = 0, $allocated_qty = 0, $description = "")
    {
        // UPDATE STOCK AVAILABILITY
        $this->availabilityRepository = \App::make(\App\Repositories\ProductAvailabilityRepository::class);
        $this->availabilityRepository->updateStock($company_id, $product_id, $qty, $location_id, $operator, $type, $ref_code, $on_order_qty, $allocated_qty, $description);
    }
}
