<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\DiscountApplicationRepository;
use Modules\Sales\Transformers\DiscountApplicationResource;

class DiscountApplicationController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(DiscountApplicationRepository $repository, DiscountApplicationResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
