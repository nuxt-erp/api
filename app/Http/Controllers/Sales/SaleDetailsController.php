<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\ControllerService;
use App\Repositories\SaleDetailsRepository;
use App\Resources\SaleDetailsResource;

class SaleDetailsController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(SaleDetailsRepository $repository, SaleDetailsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
