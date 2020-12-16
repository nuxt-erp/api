<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ReceivingRepository;
use Modules\Inventory\Transformers\ReceivingResource;

class ReceivingController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ReceivingRepository $repository, ReceivingResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    
    public function finish($receiving_id)
    {
        $status = $this->repository->finish($receiving_id);
        return $this->send();
    }

    public function poAllocation(Request $request)
    {
        $receiving = $this->repository->poAllocation($request->all());
        lad($receiving);
        return $this->sendObjectResource($receiving, $this->resource);
    }

}
