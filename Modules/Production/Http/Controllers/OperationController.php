<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\OperationRepository;
use Modules\Production\Transformers\OperationResource;


class OperationController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(OperationRepository $repository, OperationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
