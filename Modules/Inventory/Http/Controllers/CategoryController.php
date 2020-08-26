<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\CategoryRepository;
use Modules\Inventory\Transformers\CategoryResource;

class CategoryController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(CategoryRepository $repository, CategoryResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
