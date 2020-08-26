<?php

namespace Modules\Sales\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\SaleRepository;
use Modules\Sales\Transformers\SaleResource;

class SaleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(SaleRepository $repository, SaleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
