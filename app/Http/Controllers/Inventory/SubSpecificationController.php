<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\SubSpecificationRepository;
use App\Resources\SubSpecificationResource;

class SubSpecificationController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(SubSpecificationRepository $repository, SubSpecificationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
