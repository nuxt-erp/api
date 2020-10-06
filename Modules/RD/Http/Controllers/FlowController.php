<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Entities\PhaseRole;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Repositories\FlowRepository;
use Modules\RD\Transformers\FlowResource;

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
    public function getSampleFlows(){
        $phase_id = (int)request()->query('phase_id');

        $user_roles = auth()->user()->roles()->get()->pluck('id');
        $phase_roles = PhaseRole::whereIn('role_id', $user_roles)->get();
     
        $flows = Flow::where('phase_id', $phase_id)->get();
        $i =0;
        $phases = [];

        $current_phase = Phase::find($phase_id);
        $phases[$i]['name'] = ucfirst($current_phase->name);
        $phases[$i]['value'] = $current_phase->name;
        $i++;

        foreach($flows as $flow) {
            $next = $flow->next_phase()->get()->first();
            foreach($phase_roles as $phase_role) {
                if($next->id === $phase_role->phase_id) {
                    $phases[$i]['name'] = ucfirst($next->name);
                    $phases[$i]['value'] = $next->name;
                    $i++;
                }
            }         
        }      
        
        
        return $this->sendArray($phases);
    }
}
