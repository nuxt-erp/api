<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\CustomerRepository;
use App\Resources\CustomerResource;

class CustomerController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(CustomerRepository $repository, CustomerResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
