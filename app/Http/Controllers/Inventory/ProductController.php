<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductRepository;
use App\Resources\ProductResource;

class ProductController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductRepository $repository, ProductResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
