<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\SubcategoryRepository;
use Modules\ExpensesApproval\Transformers\ExpensesRuleResource;

class SubcategoryController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(SubcategoryRepository $repository, ExpensesRuleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
