<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;

trait StockTrait
{
    public function updateStock($product_id, $qty, $location_id, $operator)
    {
        // UPDATE STOCK AVAILABILITY
        $this->availabilityRepository = \App::make(\App\Repositories\ProductAvailabilityRepository::class);
        $this->availabilityRepository->updateStock($product_id, $qty, $location_id, $operator);
    }
}
