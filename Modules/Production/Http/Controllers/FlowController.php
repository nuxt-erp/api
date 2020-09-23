<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\FlowRepository;
use Modules\Production\Transformers\FlowResource;

class FlowController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(FlowRepository $repository, FlowResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}