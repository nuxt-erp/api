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
}
