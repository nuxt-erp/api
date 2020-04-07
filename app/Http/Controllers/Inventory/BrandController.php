<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\BrandRepository;
use App\Resources\BrandResource;

class BrandController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(BrandRepository $repository, BrandResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
