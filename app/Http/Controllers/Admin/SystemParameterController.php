<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\SystemParameterRepository;
use App\Resources\SystemParameterResource;

class SystemParameterController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(SystemParameterRepository $repository, SystemParameterResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
