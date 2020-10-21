<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\ParameterTypeRepository;
use App\Resources\ParameterTypeResource;

class ParameterTypeController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ParameterTypeRepository $repository, ParameterTypeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
