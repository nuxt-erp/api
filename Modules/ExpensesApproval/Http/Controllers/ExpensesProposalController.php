<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesProposalRepository;
use Modules\ExpensesApproval\Transformers\ExpensesProposalResource;
use Illuminate\Http\Request;

class ExpensesProposalController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ExpensesProposalRepository $repository, ExpensesProposalResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }    

    public function getPendingProposals(Request $request)
    {
        $items = $this->repository->getPendingProposals($request->all());
        return $this->sendFullCollectionResponse($items, ExpensesProposalResource::class);
    }

    public function getProcessedProposals(Request $request)
    {
        $items = $this->repository->getProcessedProposals($request->all());
        return $this->sendFullCollectionResponse($items, ExpensesProposalResource::class);
    }

    public function approveProposal($id)
    {
        $item = $this->repository->approveProposal($id);
        return $this->sendObjectResource($item, ExpensesProposalResource::class);
    }

    public function disapproveProposal($id)
    {
        $item = $this->repository->disapproveProposal($id);
        return $this->sendObjectResource($item, ExpensesProposalResource::class);
    }

    public function cancelProposal($id)
    {
        $item = $this->repository->cancelProposal($id);
        return $this->sendObjectResource($item, ExpensesProposalResource::class);
    }
}
