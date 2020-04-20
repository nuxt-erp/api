<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\StockTakeDetailsRepository;
use App\Resources\StockTakeDetailsResource;

class StockTakeDetailsController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(StockTakeDetailsRepository $repository, StockTakeDetailsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
