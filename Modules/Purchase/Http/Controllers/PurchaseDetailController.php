<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Purchase\Repositories\PurchaseDetailRepository;
use Modules\Purchase\Transformers\PurchaseDetailResource;

class PurchaseDetailController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PurchaseDetailRepository $repository, PurchaseDetailResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
