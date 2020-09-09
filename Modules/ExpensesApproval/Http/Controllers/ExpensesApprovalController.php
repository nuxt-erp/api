<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesApprovalRepository;
use Modules\ExpensesApproval\Transformers\ExpensesApprovalResource;

class ExpensesApprovalController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ExpensesApprovalRepository $repository, ExpensesApprovalResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    
}
