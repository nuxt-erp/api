<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\RoleRepository;
use App\Resources\RoleResource;

class RoleController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(RoleRepository $repository, RoleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
