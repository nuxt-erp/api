<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Purchase\Repositories\PurchaseTrackingNumberRepository;
use Modules\Purchase\Transformers\PurchaseTrackingNumberResource;

class PurchaseTrackingNumberController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PurchaseTrackingNumberRepository $repository, PurchaseTrackingNumberResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
