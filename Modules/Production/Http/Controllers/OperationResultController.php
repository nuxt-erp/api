<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\OperationResultRepository;
use Modules\Production\Transformers\OperationResultResource;


class OperationResultController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(OperationResultRepository $repository, OperationResultResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
