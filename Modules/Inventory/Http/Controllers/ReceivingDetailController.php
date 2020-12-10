<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\ReceivingDetailRepository;
use Modules\Inventory\Transformers\ReceivingDetailResource;

class ReceivingDetailController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ReceivingDetailRepository $repository, ReceivingDetailResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
