<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductFamilyRepository;
use App\Resources\ProductFamilyResource;

class ProductFamilyController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ProductFamilyRepository $repository, ProductFamilyResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
