<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\LocationRepository;
use App\Resources\LocationResource;

class LocationController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(LocationRepository $repository, LocationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
