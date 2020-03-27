<?php

namespace App\Http\Controllers\Inventory;

use App\Concerns\WithAllPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\ProductAvailabilityRepository;
use App\Resources\ProductAvailabilityResource;

class AvailabilityController extends ControllerService implements WithAllPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductAvailabilityRepository $repository, ProductAvailabilityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
