<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\PhaseRepository;
use Modules\Production\Transformers\PhaseResource;

class PhaseController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PhaseRepository $repository, PhaseResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
