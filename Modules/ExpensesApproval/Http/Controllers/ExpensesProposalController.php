<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesProposalRepository;
use Modules\ExpensesApproval\Transformers\ExpensesProposalResource;

class ExpensesProposalController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ExpensesProposalRepository $repository, ExpensesProposalResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
    
}
