<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\CategoryRepository;
use Modules\ExpensesApproval\Transformers\CategoryResource;

class CategoryController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(CategoryRepository $repository, CategoryResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
