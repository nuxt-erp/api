<?php

namespace App\Http\Controllers\Inventory;

use App\Concerns\WithAllPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\LocationRepository;
use App\Resources\LocationResource;

class LocationController extends ControllerService implements WithAllPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(LocationRepository $repository, LocationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
