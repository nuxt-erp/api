<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductAttributeRepository;
use App\Resources\ProductAttributeResource;

class ProductAttributeController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ProductAttributeRepository $repository, ProductAttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
