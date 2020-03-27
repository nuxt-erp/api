<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductSpecificationRepository;
use App\Resources\ProductSpecificationResource;

class ProductSpecificationController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductSpecificationRepository $repository, ProductSpecificationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
