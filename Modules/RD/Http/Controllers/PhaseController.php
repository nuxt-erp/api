<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\PhaseRepository;
use Modules\RD\Transformers\PhaseResource;

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
