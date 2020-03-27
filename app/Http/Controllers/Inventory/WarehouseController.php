<?php

namespace App\Http\Controllers\Inventory;

use App\Concerns\WithAllPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\WarehouseRepository;
use App\Resources\WarehouseResource;

class WarehouseController extends ControllerService implements WithAllPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(WarehouseRepository $repository, WarehouseResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
