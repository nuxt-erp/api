<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesRuleRepository;
use Modules\ExpensesApproval\Transformers\ExpensesRuleResource;

class ExpensesRuleController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ExpensesRuleRepository $repository, ExpensesRuleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
