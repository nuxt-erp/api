<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\ProductFamilyAttributeRepository;
use App\Resources\ProductFamilyAttributeResource;
use Illuminate\Http\Request;

class ProductFamilyAttributeController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ProductFamilyAttributeRepository $repository, ProductFamilyAttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
