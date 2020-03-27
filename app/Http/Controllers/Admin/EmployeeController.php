<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\EmployeeRepository;
use App\Resources\EmployeeResource;

class EmployeeController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(EmployeeRepository $repository, EmployeeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
