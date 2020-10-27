<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\SalesRepRepository;
use App\Resources\SalesRepResource;

class SalesRepController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(SalesRepRepository $repository, SalesRepResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
