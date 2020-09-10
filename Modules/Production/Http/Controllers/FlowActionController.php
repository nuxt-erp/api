<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\FlowActionRepository;
use Modules\Production\Transformers\FlowActionResource;

class FlowActionController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(FlowActionRepository $repository, FlowActionResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
