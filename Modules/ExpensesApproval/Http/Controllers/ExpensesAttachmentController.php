<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesAttachmentRepository;
use Modules\ExpensesApproval\Transformers\ExpensesAttachmentResource;

class ExpensesAttachmentController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ExpensesAttachmentRepository $repository, ExpensesAttachmentResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
