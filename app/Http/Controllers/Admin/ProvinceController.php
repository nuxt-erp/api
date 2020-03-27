<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProvinceRepository;
use App\Resources\ProvinceResource;

class ProvinceController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProvinceRepository $repository, ProvinceResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
