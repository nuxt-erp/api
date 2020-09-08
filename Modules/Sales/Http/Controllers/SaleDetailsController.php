<?php

namespace Modules\Sales\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\SaleDetailsRepository;
use Modules\Sales\Transformers\SaleDetailsResource;

class SaleDetailsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(SaleDetailsRepository $repository, SaleDetailsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
