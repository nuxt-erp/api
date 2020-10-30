<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\CustomerTagRepository;
use App\Resources\CustomerTagResource;

class CustomerTagController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(CustomerTagRepository $repository, CustomerTagResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
