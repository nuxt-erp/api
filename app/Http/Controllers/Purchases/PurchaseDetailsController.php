<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\ControllerService;
use App\Repositories\PurchaseDetailsRepository;
use App\Resources\PurchaseDetailsResource;

class PurchaseDetailsController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(PurchaseDetailsRepository $repository, PurchaseDetailsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
