<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\ControllerService;
use App\Repositories\SupplierRepository;
use App\Resources\SupplierResource;

class SupplierController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(SupplierRepository $repository, SupplierResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
