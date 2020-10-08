<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Entities\PhaseRole;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
use Modules\RD\Repositories\FlowRepository;
use Modules\RD\Transformers\FlowResource;
use App\Resources\ListResource;

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
    public function getSampleFlows(Request $request){
        $phase_id = (int)$request->query('phase_id');
        $phases = Flow::phaseRole($phase_id);
        return $this->sendCollectionResponse($phases, ListResource::class);
    }
}
