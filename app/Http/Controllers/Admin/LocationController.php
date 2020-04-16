<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\LocationRepository;
use App\Resources\LocationResource;

class LocationController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(LocationRepository $repository, LocationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
