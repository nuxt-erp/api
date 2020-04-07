<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\ControllerService;
use App\Repositories\CategoryRepository;
use App\Resources\CategoryResource;

class CategoryController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(CategoryRepository $repository, CategoryResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
