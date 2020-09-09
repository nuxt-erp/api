<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\ParameterRepository;
use App\Resources\ParameterResource;

class ParameterController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ParameterRepository $repository, ParameterResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
