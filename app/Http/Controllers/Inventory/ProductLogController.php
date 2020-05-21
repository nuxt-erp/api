<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductLogRepository;
use App\Resources\ProductLogResource;

class ProductLogController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductLogRepository $repository, ProductLogResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
