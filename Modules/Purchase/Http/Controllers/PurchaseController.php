<?php

namespace Modules\Purchase\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Transformers\PurchaseResource;

class PurchaseController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(PurchaseRepository $repository, PurchaseResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

}
