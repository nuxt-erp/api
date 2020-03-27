<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\SpecificationRepository;
use App\Resources\SpecificationResource;

class SpecificationController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(SpecificationRepository $repository, SpecificationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
